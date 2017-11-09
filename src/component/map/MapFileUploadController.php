<?php

namespace rrsoacis\component\map;

use ZipArchive;

use rrsoacis\manager\MapManager;
use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;

class MapFileUploadController extends AbstractController
{
	private $mapName = '';

	public function post()
	{
		ini_set('display_errors', 1);

		$uuid = uniqid();

		if ($_POST["autoconfig"] === "1") {
			$this->mapName = preg_replace('/\.(zip|tar\.gz)$/i', '', $_FILES['userfile']['name']);
		} else {
			$this->mapName = $_POST['map_name'];
		}

		// process of receive a file
		self::receivePost($uuid);

		$ext = pathinfo($_FILES ['userfile'] ['name'], PATHINFO_EXTENSION);
		if ($ext == 'zip') {
			self::extractZip($uuid);
		} else if ($ext == 'gz') {
			self::extractTarGz($uuid);
		}
	}

	private function receivePost($uuid)
	{
		$tmpDir = Config::$ROUTER_PATH . Config::TMP_DIR_NAME;
		if (!file_exists($tmpDir)) {
			mkdir($tmpDir);
		}
		//phpinfo();

		$echoData = [];
		$echoData['uploadDir'] = realpath($tmpDir);
		$echoData['name'] = $_FILES ['userfile'] ['name'];
		$echoData['tmp_name'] = $_FILES ['userfile'] ['tmp_name'];
		//echo json_encode($echoDate);

		$ext = pathinfo($echoData['name'], PATHINFO_EXTENSION);

		//Rename
		if ($ext == 'zip') {
			move_uploaded_file($_FILES ['userfile'] ['tmp_name'], $tmpDir . "/" . $uuid . ".zip");
		} else if ($ext == 'gz') {
			move_uploaded_file($_FILES ['userfile'] ['tmp_name'], $tmpDir . "/" . $uuid . ".tar.gz");
		}
	}

	private function extractZip($uuid)
	{
		$zip = new ZipArchive();
		$tmpDir = Config::$ROUTER_PATH . Config::TMP_DIR_NAME;
		$uploadedFile = $tmpDir . "/" . $uuid . ".zip";
		$mapName = $this->mapName;

		// Open file
		$res = $zip->open($uploadedFile);

		// if success opening
		if ($res === true) {
			$mapDir = Config::$ROUTER_PATH . Config::MAPS_DIR_NAME;
			$approved = false;
			// Extract all files of the archive to target a directory
			$fileDir = $tmpDir . "/" . $uuid;
			$zip->extractTo($fileDir . '/');
			$zip->close();
			system("rm -f " . $uploadedFile);

			$check = self::checkMap($fileDir);
			$approved = $check[0];
			$fileDir = $check[1];

			if ($approved) {
				if ($_POST["autoconfig"] === "1" && file_exists($fileDir."/manifest.json")) {
					$manifest = json_decode(file_get_contents($fileDir."/manifest.json"), true);
					if ($manifest["name"] !== null && $manifest["name"] !== "") {
						$mapName = $manifest["name"];
					}
				}

				$fullName = $mapName . '_' . $uuid;

				system('mv "' . $fileDir . '" "' . $mapDir . '/' . $fullName . '"');
				system("rm -rf " . $tmpDir . '/' . $uuid);

				MapManager::addMap($fullName, $mapName);

				echo '{"status":true}';
				return true;
			}

			system("rm -rf " . $tmpDir . '/' . $uuid);
		}

		echo '{"status":false}';
		return false;
	}

	private function extractTarGz($uuid)
	{
		$tmpDir = Config::$ROUTER_PATH . Config::TMP_DIR_NAME;
		$uploadedFile = $tmpDir . "/" . $uuid . ".tar.gz";
		$mapName = $this->mapName;
		$mapDir = Config::$ROUTER_PATH . Config::MAPS_DIR_NAME;

		// Extract all files of the archive to target a directory
		$fileDir = $tmpDir . "/" . $uuid;
		if (!file_exists($fileDir)) {
			mkdir($fileDir);
		}
		$res = exec('tar xzf ' . $uploadedFile . ' -C ' . $fileDir);
		system("rm -f " . $uploadedFile);

		if ($res == 0) {
			$check = self::checkMap($fileDir);
			$approved = $check[0];
			$fileDir = $check[1];

			if ($approved) {
				if ($_POST["autoconfig"] === "1" && file_exists($fileDir."/manifest.json")) {
					$manifest = json_decode(file_get_contents($fileDir."/manifest.json"), true);
					if (isset($manifest["name"]) && $manifest["name"] !== "") {
						$mapName = $manifest["name"];
					}
				}

				$fullName = $mapName . '_' . $uuid;

				system('mv "' . $fileDir . '" "' . $mapDir . '/' . $fullName . '"');
				system("rm -rf " . $tmpDir . '/' . $uuid);

				MapManager::addMap($fullName, $mapName);

				echo '{"status":true}';
				return true;
			}
		}

		system("rm -rf " . $tmpDir . '/' . $uuid);
		echo '{"status":false}';
		return false;
	}


	private function checkMap($fileDir)
	{
		$approved = false;

		if (file_exists($fileDir . '/' . 'config')
			&& file_exists($fileDir . '/' . 'map')) {
			$approved = true;
		} else {
			$files = scandir($fileDir . '/');
			foreach ($files as $file) {
				$dir = $fileDir . '/' . $file;
				if (is_dir($dir) && $file !== '.' && $file !== '..') {
					if (file_exists($dir . '/' . 'config')
						&& file_exists($dir . '/' . 'map')) {
						$approved = true;
						$fileDir = $dir;
						break;
					}
				}
			}
		}

		return [$approved, $fileDir];
	}
}

?>
