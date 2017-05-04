<?php

namespace adf\controller;

use ZipArchive;
use Symfony\Component\Finder\Finder;

use adf\Config;
use adf\controller\AbstractController;
use adf\Agent;

class MapFileUploadController extends AbstractController {
	
	public function post() {
		
		ini_set ( 'display_errors', 1 );
		
		$uuid = uniqid();
		
		//アップロードを受け取る処理
		self::receivePost($uuid);
		
		self::extractZip($uuid);
		
		self::echoFileCheck($uuid);
		
		//TODO オアシスに登録処理
		$fileName = $_POST['map_name'];
		//$output = shell_exec("sh ". Config::$ROUTER_PATH. "ruby/add_agent.sh test " .$fileName."_".$uuid);
		
		
	}
	
	private function receivePost($uuid){
		
		$uploadDir = Config::$ROUTER_PATH. Config::UPLOAD_MAP_DIR_NAME;
		
		if (! file_exists ( $uploadDir )) {
			mkdir ( $uploadDir );
		}
		//phpinfo();
		
		$echoDate = [];
		
		$echoDate['uploadDir']  = realpath ( $uploadDir );
		
		$echoDate['name'] = $_FILES ['userfile'] ['name'];
		
		$echoDate['tmp_name'] = $_FILES ['userfile'] ['tmp_name'];
		
		//echo json_encode($echoDate);
		
		
		
		//move_uploaded_file ( $_FILES ['userfile'] ['tmp_name'], $uploadDir . "/" . $_FILES ['userfile'] ['name'] );
		move_uploaded_file ( $_FILES ['userfile'] ['tmp_name'], $uploadDir . "/" . $uuid. ".zip");
	}
	
	private function extractZip($uuid){
		
		$zip = new ZipArchive();
		
		$agentDir = Config::$ROUTER_PATH. Config::MAPS_DIR_NAME;
		
		$uploadFile = Config::$ROUTER_PATH. Config::UPLOAD_MAP_DIR_NAME . "/" . $uuid. ".zip";
		
		$fileName = $_POST['map_name'];
		
		// ZIPファイルをオープン
		$res = $zip->open($uploadFile);
		
		// zipファイルのオープンに成功した場合
		if ($res === true) {
			
			// 圧縮ファイル内の全てのファイルを指定した解凍先に展開する
			
			$fileDir = $agentDir . "/" .$fileName."_" . $uuid;
			
			$zip->extractTo($fileDir. "/");
			
			// ZIPファイルをクローズ
			$zip->close();
			
			$agent = new Agent();
			
			/*$metaData = [];
			 $metaData['name'] =$fileName;
			 $metaData['uuid'] =$uuid;
			 $metaData['upload_date'] = time();
			 $arr = json_encode($metaData);*/
			
			$agent->setName($fileName);
			$agent->setUUID($uuid);
			$agent->setUploadDate(time());
			$agent->setStatus($this->checkFile($uuid));
			$arr = $agent->getJson();
			
			file_put_contents($fileDir . "/" . Config::MAP_META_JSON , $arr);
			
		}
		
		
	}
	
	private function echoFileCheck($uuid){
		
		$status = $this->checkFile($uuid);
		
		if($status){
			echo '{"status":true}';
		}else{
			//ファイルも削除
			$agentDir = Config::$ROUTER_PATH. Config::MAPS_DIR_NAME;
			$fileName = $_POST['map_name'];
			
			$fileDir = $agentDir . "/" .$fileName."_" . $uuid;
			
			system("rm -rf {$fileDir}");
			
			echo '{"status":false}';
		}
		
	}
	
	private function checkFile($uuid){
		
		$agentDir = Config::$ROUTER_PATH. Config::MAPS_DIR_NAME;
		
		$fileName = $_POST['map_name'];
		
		$fileDir = $agentDir . "/" .$fileName."_" . $uuid;
		
		$finder = new Finder();
		
		$judgment = 0;
		
		if(count($finder->in($fileDir)->directories()->name('config'))>0)$judgment++;
		if(count($finder->in($fileDir)->directories()->name('map'))>0)$judgment++;
		
		return $judgment>1;
		
	}
	
}

?>