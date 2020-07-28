<?php

namespace rrsoacis\manager;

use Exception;
use \PDO;
use rrsoacis\system\Config;
use rrsoacis\system\Agent;
use rrsoacis\exception\AgentNotFoundException;
use ZipArchive;

class MapManager
{
	public static function getMaps()
	{
		$db = self::connectDB();
		$sth = $db->query("select * from map where archived = 0;");
		$maps = [];
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$maps[] = $row;
		}

		return $maps;
	}

	public static function getArchivedMaps()
	{
		$db = self::connectDB();
		$sth = $db->query("select * from map where archived = 1;");
		$maps = [];
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$maps[] = $row;
		}

		return $maps;
	}

	public static function getMap($name)
	{
		$db = self::connectDB();
		$sth = $db->prepare("select * from map where name=:name;");
		$sth->bindValue(':name', $name, PDO::PARAM_STR);
		$sth->execute();
		$map = null;
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$map = $row;
		}

		return $map;
	}

	public static function getMapByAlias($alias)
	{
		$db = self::connectDB();
		$sth = $db->prepare("select * from map where archived = 0 and alias=:alias;");
		$sth->bindValue(':alias', $alias, PDO::PARAM_STR);
		$sth->execute();
		$map = null;
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$map = $row;
		}

		return $map;
	}

	public static function setArchived($name, $archived)
	{
		$db = self::connectDB();
		if ($archived == 0) {
			$agent = self::getMap($name);

			$sth = $db->prepare("update map set archived=1 where alias=:alias;");
			$sth->bindValue(':alias', $agent['alias'], PDO::PARAM_STR);
			$sth->execute();
		}

		$sthU = $db->prepare("update map set archived=:archived where name=:name;");
		$sthU->bindValue(':archived', $archived, PDO::PARAM_INT);
		$sthU->bindValue(':name', $name, PDO::PARAM_STR);
		$sthU->execute();
	}

	public static function addMap($name, $alias)
	{
		$db = self::connectDB();
		$sth = $db->prepare("update map set archived=1 where alias=:alias;");
		$sth->bindValue(':alias', $alias, PDO::PARAM_STR);
		$sth->execute();
		$sth = $db->prepare("insert into map(name, alias) values(:name, :alias);");
		$sth->bindValue(':name', $name, PDO::PARAM_STR);
		$sth->bindValue(':alias', $alias, PDO::PARAM_STR);
		$sth->execute();

		self::updateMapImage($name);
	}

	public static function updateManifest($name)
	{
		$map = self::getMap($name);
		if ($map === null) { return; }

		$manifest = [];
		$manifest["name"] = $map["alias"];

		$manifestPath = Config::$ROUTER_PATH . Config::MAPS_DIR_NAME."/".$name."/.manifest.json";
		file_put_contents($manifestPath, json_encode($manifest));
	}

	public static function updateMapImage($name)
	{
		$map = self::getMap($name);
		if ($map === null) { return; }

		$scriptId = uniqid();
		$simulatorName = self::getMapImageSimulator();

		$out_filename = '/tmp/out_' . $scriptId . '.json';

		$command = Config::$OACISCLI_PATH . " create_parameter_sets";
		$command .= ' -s ' . $simulatorName;
		$command .= ' -i \'{"MAP":"'.$map["name"].'"}\'';
		$command .= ' -r \'{"num_runs":1,"mpi_procs":0,"omp_threads":0,"priority":1,"submitted_to":"' . ClusterManager::getMainHostGroup() . '","host_parameters":null}\'';
		$command .= ' -o ' . $out_filename;
		$command .= "\n";
		$command .= "rm -f ".$out_filename;

		ScriptManager::queueBashScript($command);
	}

	public static function getMapImageSimulator()
	{
		$db = self::connectDB();
		$sth = $db->query("select value from system where name='mapImageSimulator';");
		$simulatorName = $sth->fetch(PDO::FETCH_ASSOC)['value'];

		if (@file_get_contents('http://127.0.0.1:3000/simulators/'.$simulatorName.'.json') === false) {
			$tmpFileOut = '/tmp/rrsoacis-out-' . uniqid();
			$tmpFileIn = '/tmp/rrsoacis-in-' . uniqid();
			system("bash -l -c '" . Config::$OACISCLI_PATH . " simulator_template -o " . $tmpFileOut . "' 2>&1");
			$simulator = json_decode(file_get_contents($tmpFileOut), true);
			system("rm -f " . $tmpFileOut);
			$simulator['name'] = "RO_MapImage_" . uniqid();
			$simulator['command'] = '/home/oacis/rrs-oacis/rrsenv/script/rrscluster update-mapimage -c ../rrscluster.cfg -i ./_input.json -l ./';
			$simulator['executable_on_ids'][] = ClusterManager::getMainHostGroup();
			$simulator['support_input_json'] = true;

			$simulator['parameter_definitions'] = [];

			$parameter1 = [];
			$parameter1['key'] = 'MAP';
			$parameter1['type'] = 'String';
			$parameter1['default'] = 'dummy';
			$parameter1['description'] = '';
			$simulator['parameter_definitions'][] = $parameter1;

			file_put_contents($tmpFileIn, json_encode($simulator));
			system("bash -l -c '" . Config::$OACISCLI_PATH . " create_simulator -i " . $tmpFileIn . " -o " . $tmpFileOut. "'");
			system("rm -f " . $tmpFileIn);
			$simulatorId = json_decode(file_get_contents($tmpFileOut), true)['simulator_id'];
			system("rm -f " . $tmpFileOut);

			$sth = $db->prepare('update system set value=:name where name="mapImageSimulator";');
			$sth->bindValue(':name', $simulatorId, PDO::PARAM_STR);
			$sth->execute();

			return $simulatorId;
		}

		return $simulatorName;
	}

	public static function getZipBinary($name)
	{
		$dirPath = Config::$ROUTER_PATH . Config::MAPS_DIR_NAME . "/" . $name;

		$zipTmpDir = '/tmp/rrsoacis';
		if (!is_dir($zipTmpDir)) {
			mkdir($zipTmpDir, '0777', TRUE);
		}

		$zipDirName = 'map_' . $name;
		$zipFileName = $zipDirName . '.zip';
		if (file_exists($zipTmpDir . $zipFileName)) {
			unlink($zipTmpDir . $zipFileName);
		}

		$zip = new ZipArchive();
		if ($zip->open($zipTmpDir . $zipFileName, ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE)) {
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

	private static function getFilePathsArray($dir_path)
	{
		$file_array = array();
		if (is_dir($dir_path)) {
			if ($dh = opendir($dir_path)) {
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
		$sth = $db->query("select value from system where name='mapVersion';");
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$connectedAppVersion = $row['value'];
		}

		switch ($connectedAppVersion) {
			case 0:
				$db->query("insert into system(name,value) values('mapVersion', 1);");
				$db->query("create table map(name,alias,archived default 0,timestamp default (DATETIME('now','localtime')));");
			case 1:
				$db->query("insert into system(name,value) values('mapImageSimulator', '');");
				$version = 2;

				$sth = $db->prepare("update system set value=:value where name='mapVersion';");
				$sth->bindValue(':value', $version, PDO::PARAM_INT);
				$sth->execute();
			default:
		}

		return $db;
	}
}

