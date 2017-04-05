<?php

namespace adf\controller;

use ZipArchive;

use adf\Config;
use adf\controller\AbstractController;

class FileUploadController extends AbstractController {
	
	public function post() {
		
		ini_set ( 'display_errors', 1 );
		
		$uuid = uniqid();
		
		//アップロードを受け取る処理
		self::receivePost($uuid);
		
		self::extractZip($uuid);
		
	}
	
	private function receivePost($uuid){
		
		$uploadDir = Config::$ROUTER_PATH. Config::UPLOAD_DIR_NAME;
		
		if (! file_exists ( $uploadDir )) {
			mkdir ( $uploadDir );
		}
		//phpinfo();
		
		echo realpath ( $uploadDir ) . " : ";
		
		echo $_FILES ['userfile'] ['name'] . " : ";
		
		echo $_FILES ['userfile'] ['tmp_name'];
		
		//move_uploaded_file ( $_FILES ['userfile'] ['tmp_name'], $uploadDir . "/" . $_FILES ['userfile'] ['name'] );
		move_uploaded_file ( $_FILES ['userfile'] ['tmp_name'], $uploadDir . "/" . $uuid. ".zip");
	}
	
	private function extractZip($uuid){
		
		$zip = new ZipArchive();
		
		$agentDir = Config::$ROUTER_PATH. Config::AGENTS_DIR_NAME;
		
		$uploadFile = Config::$ROUTER_PATH. Config::UPLOAD_DIR_NAME . "/" . $uuid. ".zip";
		
		$fileName = $_POST['agent_name'];
		
		// ZIPファイルをオープン
		$res = $zip->open($uploadFile);
		
		// zipファイルのオープンに成功した場合
		if ($res === true) {
			
			// 圧縮ファイル内の全てのファイルを指定した解凍先に展開する
			
			$fileDir = $agentDir . "/" .$fileName."_" . $uuid;
			
			$zip->extractTo($fileDir. "/");
			
			// ZIPファイルをクローズ
			$zip->close();
			
			$metaData = [];
			$metaData['name'] =$fileName;
			$metaData['uuid'] =$uuid;
			$metaData['upload_date'] = time();
			$arr = json_encode($metaData);
			file_put_contents($fileDir . "/" . Config::AGENT_META_JSON , $arr);
			
		}
		
		
	}
	
}

?>