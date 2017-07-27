<?php
namespace adf\apps\results\model;

use ZipArchive;
use adf\Config;
use adf\apps\results\ResultController;
use adf\apps\results\model\ResultHelper;
use adf\apps\results\ResultMapController;
use adf\apps\competition\SessionManager;

class ResultDownload{
	
	public static function downloadPage($simulationID,$preParam= null){
		
		//echo 'aaa';
		//return ;
		// Zipクラスロード
		$zip = new ZipArchive();
		// Zipファイル名
		$zipFileName = 'result_'.$simulationID.'.zip';
		// Zipファイル一時保存ディレクトリ
		//$zipTmpDir = '/home/oacis/rrs-oacis/data/tmp/zip/download/';
		//$zipTmpDir = '/home/oacis/rrs-oacis/data/tmp/';
		$zipTmpDir = '/tmp/';

		//echo $zipTmpDir;
		
		if(!file_exists($zipTmpDir)){
			
			if(mkdir($zipTmpDir,'0777', TRUE)){
				
			}
			
		}
		
		// Zipファイルオープン
		$result = $zip->open($zipTmpDir.$zipFileName, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
		if ($result !== true) {
			// 失敗した時の処理
			echo 'Error';
		}
		
		$urlBase = Config::$RESOURCE_PATH;
		
		
		//$zip->addFromString('index.html', file_get_contents($urlBase.'result/'.$simulationID));
		
		$rc = new ResultController();
		
		$zip->addEmptyDir( 'result_'.$simulationID);

		
		
		$zip->addFromString('result_'.$simulationID.'/index.html', $rc->downloadHTML($simulationID,$preParam));
		
		$zip->addEmptyDir( 'result_'.$simulationID.'/results_map/'.$simulationID);
		
		
		$parameterSets= ResultHelper::getParameterSets($simulationID);
		
		$maps = ResultHelper::getMaps($parameterSets);
		
		$teams = ResultHelper::getTeams($simulationID, $parameterSets);
		
		ResultHelper::calPoints($teams);
		
		ResultHelper::addRank($teams);

		$data = SessionManager::getSessions();
		
		for($i=0;$i<count($maps);$i++){
			
			$mapName = $maps[$i];
			
			$mapURL = 'result_'.$simulationID.'/results_map/'.$simulationID.'/'.$mapName;
			
			$zip->addEmptyDir( $mapURL);
			
			$rmc = new ResultMapController();
			
			$zip->addFromString($mapURL.'/index.html', $rmc->downloadHTML($simulationID,$mapName));
			
			
			//
			$stepSize = ResultHelper::getMapStep4Teams($teams, $mapName);
			
			$step = ResultHelper::getMapStepArray($stepSize);
			
			foreach ($teams as $key => $value){
				
				$map_a = $mapURL.'/'.$key;
				
				$zip->addEmptyDir( $map_a);
				
				$map_a_map = $map_a . "/" .Config::MAP_LOG;
				
				$zip->addEmptyDir($map_a_map);
				
				$mapurl = $value->getMapLogURI($mapName).'/snapshot-init.png';
				
				$png_text = @file_get_contents($mapurl);
				
				if($png_text==null){
					$png_text = 'null';
				}
				
				//$zip->addFromString($map_a_map . '/snapshot-init',file_get_contents($mapurl));
				$zip->addFromString($map_a_map . '/snapshot-init.png',$png_text);
				
				//Step Image
				for($j=0;$j<count($step);$j++){
					
					$URL_S = $value->getMapLogURI($mapName). '/snapshot-'.$step[$j] . '.png';
				
					$png_step_text = @file_get_contents($URL_S);
					
					if($png_step_text==null){
						$png_step_text= 'null';
					}
					
					//$zip->addFromString($map_a_map . '/snapshot-init',file_get_contents($mapurl));
					$zip->addFromString($map_a_map . '/snapshot-'.$step[$j] . '.png',$png_step_text);
					
				}

				//Final
				$URL_S = $value->getMapLogURI($mapName). '/snapshot-final.png';

                                $png_step_text = @file_get_contents($URL_S);

                                if($png_step_text==null){
                                        $png_step_text= 'null';
                                }

                                $zip->addFromString($map_a_map . '/snapshot-final.png',$png_step_text);

				
				
				
			}
			
			//Download Map data
			$mapURL = 'result_'.$simulationID.'/results_map/'.$simulationID.'/'.$mapName;

			for($k=0;$k<count($data);$k++){

				if( $data[$k]["name"]!= $simulationID )continue;

				for($j=0;$j<count($data[$k]["maps"]);$j++){

					$mapDateName= $data[$k]["maps"][$j]["name"];
					$mapAliasDateName= $data[$k]["maps"][$j]["alias"];
					if( $mapAliasDateName!= $mapName ) continue;

					//MapのDownload

					$mapDir = Config::$ROUTER_PATH.Config::MAPS_DIR_NAME;
					$path = $mapDir . '/' . $mapDateName;

					
					$shell = shell_exec('cd '.$path. ';tar cfvz '.$mapAliasDateName.'.tar.gz .');

					$zipDate = @file_get_contents($path.'/'.$mapAliasDateName.'.tar.gz');

					$zip->addFromString($mapURL . '/map.tar.gz',$zipDate);

					shell_exec('cd '.$path. ';rm '.$mapAliasDateName.'.tar.gz');

					

				}

				

			}

			
		}
		
		
		
		/*
		foreach ($image_data_array as $filepath) {
			
			$filename = basename($filepath);
			
			// 取得ファイルをZipに追加していく
			$zip->addFromString($filename,file_get_contents($filepath));
			
		}*/
		
		$zip->close();
		
		// ストリームに出力
		header('Content-Type: application/zip; name="' . $zipFileName . '"');
		header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
		header('Content-Length: '.filesize($zipTmpDir.$zipFileName));
		echo file_get_contents($zipTmpDir.$zipFileName);
		
		// 一時ファイルを削除しておく
		unlink($zipTmpDir.$zipFileName);
		
		
	}
	
}


