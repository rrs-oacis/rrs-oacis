<?php

namespace rrsoacis\manager;

use \MongoClient;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use \PDO;
use rrsoacis\system\Config;
use rrsoacis\system\Agent;
use rrsoacis\exception\AgentNotFoundException;

class ClusterManager
{
	const MAIN_HOST_GROUP = 'RRS-OACIS';

	/**
	 *
	 * */
	public static function getClusters()
	{
		$db = self::connectDB();
		$sth = $db->query("select * from cluster;");
		$clusters = [];
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$clusters [] = $row;
		}

		return $clusters;
	}

	public static function getCluster($name)
	{
		$db = self::connectDB();
		$sth = $db->query("select * from cluster where name='" . $name . "';");
		$cluster = null;
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$cluster = $row;
		}

		return $cluster;
	}

	public static function getClusterRawCheckMessage($name)
	{
		$workspace = Config::$ROUTER_PATH . Config::WORKSPACE_DIR_NAME . "/" . $name;
		exec('cd ' . $workspace . '; ../../script/rrscluster check', $out, $ret);
		$messages = implode("\n", $out);
		$checkMessageArray = preg_grep("/^@.\d/", $out);

		$hasError = [];
		$javaVer = [];
		foreach (["S", "A", "F", "P"] as $alias) {
			$hasError[$alias] = false;
			$javaVer[$alias] = "";
		}

		foreach ($checkMessageArray as $msg) {
			$errorCode = preg_replace('/^@(.\d)/', '${1}', $msg);
			$node = substr($errorCode, 0, 1);
			$code = intval(substr($errorCode, 1));
			if ($code == 0) {
				$javaVer[$node] = preg_replace('/^@.\d (.+)$/', '${1}', $msg);
			} else {
				$hasError[$node] = true;
			}
		}

		$errorCount = 0;
		foreach (["S", "A", "F", "P"] as $alias) {
			if ($javaVer[$alias] == "") {
				$hasError[$alias] = true;
			}

			if ($hasError[$alias]) {
				$errorCount++;
			}
		}

		$db = self::connectDB();
		if ($errorCount <= 0) {
			$sth = $db->prepare("update cluster set check_status=0 where name=:name;");
			$sth->bindValue(':name', $name, PDO::PARAM_STR);
			$sth->execute();
		} else {
			$sth = $db->prepare("update cluster set check_status=2 where name=:name and check_status!=3;");
			$sth->bindValue(':name', $name, PDO::PARAM_STR);
			$sth->execute();
		}


		return $messages;
	}

	public static function updateAllStatus()
	{
		foreach (self::getClusters() as $cluster) {
			self::updateStatus($cluster["name"]);
		}
	}

	public static function updateStatus($name)
	{
		$cluster = self::getCluster($name);
		if ($cluster != null) {
			if ($cluster["check_status"] != 3) {
				$db = self::connectDB();
				$db->query("update cluster set check_status=1 where name='" . $cluster["name"] . "' and check_status!=3;");

				$scriptId = uniqid();
				$script = "#!/bin/bash\n\n";
				$script .= 'php ' . realpath(dirname(__FILE__)) . '/update_status.php \'' . $cluster["name"] . '\' >>/tmp/t';
				file_put_contents('/home/oacis/rrs-oacis/oacis-queue/scripts/' . $scriptId, $script);
				exec('nohup /home/oacis/rrs-oacis/oacis-queue/main.pl ' . $scriptId . ' >/dev/null &');
			}
			self::updateHostGroup();
		}
	}

	public static function setupHosts($name, $pass)
	{
		$cluster = self::getCluster($name);
		if ($cluster != null) {
			$script = 'cd /home/oacis/rrs-oacis/rrsenv/workspace/' . $cluster["name"] . ' ; ../../script/rrscluster setup -p \'' . $pass . '\'';
			ScriptManager::queueBashScript($script);
		}
	}

	public static function sendKillSignal($name)
	{
		$cluster = self::getCluster($name);
		if ($cluster != null) {
			$script = 'cd /home/oacis/rrs-oacis/rrsenv/workspace/' . $cluster["name"] . ' ; ../../script/rrscluster kill';
			ScriptManager::queueBashScript($script);
		}
	}

	public static function getMainHostGroup()
	{
		$db = self::connectDB();
		$sth = $db->query("select value from system where name='mainHostGroupName';");
		$name = $sth->fetch(PDO::FETCH_ASSOC)['value'];
		return $name;
	}

	public static function removeCluster($name)
	{
		$db = self::connectDB();
		$sth = $db->prepare("select count(*) as num from cluster where name=:name;");
		$sth->bindValue(':name', $name, PDO::PARAM_STR);
		$sth->execute();
		$num = $sth->fetch(PDO::FETCH_ASSOC)['num'];

		if ($num > 0) {
			$sth = $db->prepare("delete from cluster where name=:name;");
			$sth->bindValue(':name', $name, PDO::PARAM_STR);
			$sth->execute();

			$workspace = Config::$ROUTER_PATH . Config::WORKSPACE_DIR_NAME . "/" . $name;
			if (file_exists($workspace)) {
				system('rm -rf ' . $workspace);
			}

			/* BEGIN : direct OACIS control */
			$oacisdb = self::connectOacisDB();
			$oaciscoll = $oacisdb->selectCollection("hosts");
			$oaciscoll->deleteMany(array("name" => "RO_" . $name));
			/* END : direct OACIS control */
		}

		self::updateHostGroup();
	}

	public static function setEnable($clusterName)
	{
		$db = self::connectDB();
		$sth = $db->prepare("update cluster set check_status=1 where name=:name;");
		$sth->bindValue(':name', $clusterName, PDO::PARAM_STR);
		$sth->execute();
	}

	public static function setDisable($clusterName)
	{
		$db = self::connectDB();
		$sth = $db->prepare("update cluster set check_status=3 where name=:name;");
		$sth->bindValue(':name', $clusterName, PDO::PARAM_STR);
		$sth->execute();
	}

	/**
	 *
	 * */
	public static function updateCluster($name, $a_host, $f_host, $p_host, $s_host, $archiver = "gzip", $hosts_pass = "")
	{
		$workspaceDir = Config::$ROUTER_PATH . Config::WORKSPACE_DIR_NAME;
		if (!file_exists($workspaceDir)) {
			mkdir($workspaceDir);
		}

		$db = self::connectDB();
		$sth = $db->prepare("select count(*) as num from cluster where name=:name;");
		$sth->bindValue(':name', $name, PDO::PARAM_STR);
		$sth->execute();
		$num = $sth->fetch(PDO::FETCH_ASSOC)['num'];

		if ($num > 0) {
			$sth = $db->prepare("update cluster set a_host=:a_host, f_host=:f_host, p_host=:p_host, s_host=:s_host, archiver=:archiver where name=:name;");
		} else {
			/* BEGIN : direct OACIS control */
			$oacisdb = self::connectOacisDB();
			$oaciscoll = $oacisdb->selectCollection("hosts");
			$hosts = $oaciscoll->find(array("name" => "localhost"));
			foreach ($hosts as $entry) {
				$base = $entry;
			}

			$name = new ObjectID();

			$myWorkspaceDir = Config::$ROUTER_PATH . Config::WORKSPACE_DIR_NAME . '/' . $name;
			mkdir($myWorkspaceDir);

			$myWorkspaceDir = '~/rrs-oacis/' . Config::WORKSPACE_DIR_NAME . '/' . $name;
			$base['_id'] = $name;
			$base['name'] = 'RO_' . $name;
			$base['work_base_dir'] = $myWorkspaceDir;
			$base['mounted_work_base_dir'] = ''; //$myWorkspaceDir;
			$oaciscoll->insertOne($base);
			/* END : direct OACIS control */

			$sth = $db->prepare("insert into cluster(name, a_host, f_host, p_host, s_host, archiver) values(:name, :a_host, :f_host, :p_host, :s_host, :archiver);");
		}

		$myWorkspaceDir = Config::$ROUTER_PATH . Config::WORKSPACE_DIR_NAME . '/' . $name;

		$config = "SERVER_SS=\"" . $s_host . "\"\nSERVER_C1=\"" . $f_host . "\"\nSERVER_C2=\"" . $p_host . "\"\nSERVER_C3=\"" . $a_host . "\"\nARCHIVER=\"" . $archiver . "\"\n";
		file_put_contents($myWorkspaceDir . '/config.cfg', $config);
		//------------------
		$config = "{ 'server'=>'" . $s_host . "', 'fire'=>'" . $f_host . "', 'police'=>'" . $p_host . "', 'ambulance'=>'" . $a_host . "', 'archiver'=>'" . $archiver . "' }";
		file_put_contents($myWorkspaceDir . '/rrscluster.cfg', $config);
		//------------------
		system("chown -R oacis:oacis " . Config::$ROUTER_PATH . Config::WORKSPACE_DIR_NAME);
		$myWorkspaceDir = '~/rrs-oacis/' . Config::WORKSPACE_DIR_NAME . '/' . $name;

		$sth->bindValue(':name', $name, PDO::PARAM_STR);
		$sth->bindValue(':a_host', $a_host, PDO::PARAM_STR);
		$sth->bindValue(':f_host', $f_host, PDO::PARAM_STR);
		$sth->bindValue(':p_host', $p_host, PDO::PARAM_STR);
		$sth->bindValue(':s_host', $s_host, PDO::PARAM_STR);
		$sth->bindValue(':archiver', $archiver, PDO::PARAM_STR);
		$sth->execute();

		self::setupHosts($name, $hosts_pass);
		self::updateStatus($name);
		self::updateHostGroup();
	}

	/**
	 *
	 * */
	public static function updateHostGroup()
	{
		$oacisdb = self::connectOacisDB();

		$db = self::connectDB();
		$sth = $db->query("select count(*) as num from cluster where check_status=0;");
		$num = $sth->fetch(PDO::FETCH_ASSOC)['num'];

		// destroy HostGroup in all case
		$sth = $db->query("select value from system where name='mainHostGroupName';");
		$nameText = $sth->fetch(PDO::FETCH_ASSOC)['value'];
		if ($nameText === '') // if already exist HostGroup
		{
			$name = new ObjectID();
			$sth = $db->prepare("update system set value=:value where name='mainHostGroupName';");
			$sth->bindValue(':value', $name, PDO::PARAM_STR);
			$sth->execute();
		} else {
			$name = new ObjectID($nameText);
		}

		$oaciscoll = $oacisdb->selectCollection("host_groups");
		$oaciscoll->deleteMany(array("name" => self::MAIN_HOST_GROUP));

		if ($num > 0) // if exist cluster
		{ // setup HostGroup

			/* BEGIN : direct OACIS control */
			// setup HostGroup on mongo
			// e.g. {
			//"_id" : ObjectId("593697bae011d900edec2f3c"),
			//"name" : "RRS-OACIS",
			//"host_ids" : [ ObjectId("593691f518d168898099a4ba"),
			//ObjectId("593696c5e011d9000a7f1674"),
			//ObjectId("59369705eosts011d9000a7f1675") ],
			//"updated_at" : ISODate("2017-06-06T11:53:30.082Z"),
			//"created_at" : ISODate("2017-06-06T11:53:30.082Z")
			//}

			$hostGroup["_id"] = $name;
			$hostGroup["name"] = self::MAIN_HOST_GROUP;
			$sth = $db->query("select name from cluster where check_status=0;");
			$hostGroup["host_ids"] = [];
			$oaciscoll = $oacisdb->selectCollection("hosts");
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$hostid = new ObjectID($row['name']);
				$hostGroup["host_ids"][] = $hostid;
				$oaciscoll->updateOne(array("_id" => $hostid), array('$set' => array("host_group_ids" => array($name))));
			}
			$hostGroup["updated_at"] = new UTCDateTime();
			$hostGroup["created_at"] = new UTCDateTime();
			$oaciscoll = $oacisdb->selectCollection("host_groups");
			$oaciscoll->insertOne($hostGroup);
			/* END : direct OACIS control */
		} else {
			$hostGroup["_id"] = $name;
			$hostGroup["name"] = self::MAIN_HOST_GROUP;
			$hostGroup["host_ids"] = [];
			$oaciscoll = $oacisdb->selectCollection("hosts");
			$hostGroup["updated_at"] = new UTCDateTime();
			$hostGroup["created_at"] = new UTCDateTime();
			$oaciscoll = $oacisdb->selectCollection("host_groups");
			$oaciscoll->insertOne($hostGroup);
		}

		$sth = $db->query("select name from cluster where check_status!=0;");
		$oaciscoll = $oacisdb->selectCollection("hosts");
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$hostid = new ObjectID($row['name']);
			$oaciscoll->updateOne(array("_id" => $hostid), array('$set' => array("host_group_ids" => array())));
		}
	}

	/**
	 *
	 * */
	private static function connectDB()
	{
		$db = DatabaseManager::getSystemDatabase();
		$connectedAppVersion = 0;
		$sth = $db->query("select value from system where name='clusterVersion';");
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$connectedAppVersion = $row['value'];
		}

		switch ($connectedAppVersion) {
			case 0:
				$db->query("insert into system(name,value) values('clusterVersion', 1);");
				$db->query("insert into system(name,value) values('mainHostGroupName', '');");
				$db->query("create table cluster(name, a_host, f_host, p_host);");
			case 1:
				$db->query("alter table cluster add s_host;");
			case 2:
				$db->query("alter table cluster add check_status default 1;");
			case 3:
				$db->query("alter table cluster add archiver default 'gzip';");
				$version = 4;

				$sth = $db->prepare("update system set value=:value where name='clusterVersion';");
				$sth->bindValue(':value', $version, PDO::PARAM_INT);
				$sth->execute();
			default:
		}

		return $db;
	}

	/**
	 *
	 * */
	private static function connectOacisDB()
	{
		$client = new \MongoDB\Client("mongodb://localhost:27017");
		$db = $client->oacis_development;

		return $db;
	}
}

