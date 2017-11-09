<?php

namespace rrsoacis\manager;

use Exception;
use \PDO;
use rrsoacis\system\Config;
use rrsoacis\system\Agent;
use rrsoacis\exception\AgentNotFoundException;
use ZipArchive;

class AgentManager
{
	public static function getAgents()
	{
		$db = self::connectDB();
		$sth = $db->query("select * from agent where archived = 0;");
		$agents = [];
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$agents[] = $row;
		}

		return $agents;
	}

	public static function getArchivedAgents()
	{
		$db = self::connectDB();
		$sth = $db->query("select * from agent where archived = 1;");
		$agents = [];
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$agents[] = $row;
		}

		return $agents;
	}

	public static function getAgent($name)
	{
		$db = self::connectDB();
		$sth = $db->prepare("select * from agent where name=:name;");
		$sth->bindValue(':name', $name, PDO::PARAM_STR);
		$sth->execute();
		$agent = [];
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$agent = $row;
		}

		return $agent;
	}

	public static function getAgentByAlias($alias)
	{
		$db = self::connectDB();
		$sth = $db->prepare("select * from agent where archived = 0 and alias=:alias;");
		$sth->bindValue(':alias', $alias, PDO::PARAM_STR);
		$sth->execute();
		$agent = [];
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$agent = $row;
		}

		return $agent;
	}

	public static function setArchived($name, $archived)
	{
		$db = self::connectDB();

		if ($archived == 0) {
			$agent = self::getAgent($name);

			$sth = $db->prepare("update agent set archived=1 where alias=:alias;");
			$sth->bindValue(':alias', $agent['alias'], PDO::PARAM_STR);
			$sth->execute();
		}

		$sthU = $db->prepare("update agent set archived=:archived where name=:name;");
		$sthU->bindValue(':archived', $archived, PDO::PARAM_INT);
		$sthU->bindValue(':name', $name, PDO::PARAM_STR);
		$sthU->execute();
	}

	public static function addAgent($name, $alias)
	{
		$db = self::connectDB();
		$sth = $db->prepare("update agent set archived=1 where alias=:alias;");
		$sth->bindValue(':alias', $alias, PDO::PARAM_STR);
		$sth->execute();
		$sth = $db->prepare("insert into agent(name, alias) values(:name, :alias);");
		$sth->bindValue(':name', $name, PDO::PARAM_STR);
		$sth->bindValue(':alias', $alias, PDO::PARAM_STR);
		$sth->execute();
	}

	public static function updateManifest($name)
	{
		$agent = self::getAgent($name);
		if ($agent === null) { return; }

		$manifest = [];
		$manifest["name"] = $agent["alias"];

		$manifestPath = Config::$ROUTER_PATH . Config::AGENTS_DIR_NAME."/".$name."/manifest.json";
		file_put_contents($manifestPath, json_encode($manifest));
	}

	public static function getZipBinary($name)
	{
		$dirPath = Config::$ROUTER_PATH . Config::AGENTS_DIR_NAME . "/" . $name;

		$zipTmpDir = '/tmp/rrsoacis';
		if (!is_dir($zipTmpDir)) {
			mkdir($zipTmpDir, '0777', TRUE);
		}

		$zipDirName = 'agent_' . $name;
		$zipFileName = $zipDirName . '.zip';
		if (file_exists($zipTmpDir . $zipFileName)) {
			unlink($zipTmpDir . $zipFileName);
		}

		$zip = new ZipArchive();
		if ($zip->open($zipTmpDir . $zipFileName, ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE) === true) {
			self::addZip($zip, $dirPath, $zipDirName);
			$zip->close();
		} else {
			throw new Exception('Could not make a zip file');
		}

		$output = file_get_contents($zipTmpDir . $zipFileName);

		unlink($zipTmpDir . $zipFileName);

		return $output;
	}

	private static function addZip($zip, $dirPath, $newDir)
	{
		if (!is_dir($newDir)) {
			$zip->addEmptyDir($newDir);
		}

		foreach (self::getFilePathsArray($dirPath) as $file) {
			if (is_dir($dirPath . "/" . $file)) {
				self::addZip($zip, $dirPath . "/" . $file, $newDir . "/" . $file);
			} else {
				$zip->addFile($dirPath . "/" . $file, $newDir . "/" . $file);
			}
		}
	}

	public static function getFilePathsArray($dirPath)
	{
		$file_array = array();
		if (is_dir($dirPath)) {
			if ($dh = opendir($dirPath)) {
				while (($file = readdir($dh)) !== false) {
					if ($file == "." || $file == "..") {
						continue;
					}
					$file_array[] = $file;
				}
				closedir($dh);
			}
		}
		sort($file_array);
		return $file_array;
	}

	/**
	 *
	 * */
	private static function connectDB()
	{
		$db = DatabaseManager::getSystemDatabase();
		$connectedAppVersion = 0;
		$sth = $db->query("select value from system where name='agentVersion';");
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$connectedAppVersion = $row['value'];
		}

		switch ($connectedAppVersion) {
			case 0:
				$db->query("insert into system(name,value) values('agentVersion', 1);");
				$db->query("create table agent(name,alias,archived default 0,timestamp default (DATETIME('now','localtime')));");
				$version = 1;

				$sth = $db->prepare("update system set value=:value where name='agentVersion';");
				$sth->bindValue(':value', $version, PDO::PARAM_INT);
				$sth->execute();
			default:
		}

		return $db;
	}
}

