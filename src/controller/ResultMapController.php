<?php
namespace adf\controller;

use adf\controller\AbstractController;
use adf\model\MapResultGeneration;
use adf\model\ResultHelper;

class ResultMapController extends AbstractController{
	
	public function anyIndex($param= null,$param2= null){
		return self::get($param,$param2);
	}
	
	public function get($simulatorID= null,$mapName= null){
		
		//Getteam
		$parameterSets= ResultHelper::getParameterSets($simulatorID);
		
		$maps = ResultHelper::getMaps($parameterSets);
		
		$teams = ResultHelper::getTeams($simulatorID, $parameterSets);
		
		ResultHelper::calPoints($teams);
		
		ResultHelper::addRank($teams);
		
		$this->addMapRank($teams, $mapName);
		
		$stepSize = ResultHelper::getMapStep4Teams($teams, $mapName);
		
		$step = ResultHelper::getMapStepArray($stepSize);
		
		return MapResultGeneration::generateHTML('2018', $simulatorID, $mapName, $teams,$step);
		
		
	}
	
	public function addMapRank(array $teams, $mapName){
		
		//Add rank
		$key_id = [];
		
		$teams2 = $teams;
		foreach ($teams2 as $key => $value){
			$key_id[$key] = $value->getScore($mapName)['points'];
		}
		array_multisort ( $key_id , SORT_DESC, $teams2);
		
		$rank = 1;
		foreach ($teams2 as $key => $value){
			
			$teams[$key]->setRank($rank);
			$rank++;
		}
		
	}
	
	public function downloadHTML($simulatorID= null,$mapName= null){
		
		//Getteam
		$parameterSets= ResultHelper::getParameterSets($simulatorID);
		
		$maps = ResultHelper::getMaps($parameterSets);
		
		$teams = ResultHelper::getTeams($simulatorID, $parameterSets,true);
		
		ResultHelper::calPoints($teams);
		
		ResultHelper::addRank($teams);
		
		$this->addMapRank($teams, $mapName);
		
		$stepSize = ResultHelper::getMapStep4Teams($teams, $mapName);
		
		$step = ResultHelper::getMapStepArray($stepSize);
		
		return MapResultGeneration::generateHTML('2018', $simulatorID, $mapName, $teams,$step);
		
	}
	
	
	
	//simulator , map , team, type
	//Not use curl
	/*
	public function anyImages($simulatorID,$map,$team,$type)
	{
		//Getteam
		$parameterSets= ResultHelper::getParameterSets($simulatorID);
		
		$maps = ResultHelper::getMaps($parameterSets);
		
		$teams = ResultHelper::getTeams($simulatorID, $parameterSets);
		
		$mapuri = $teams[$team]->getMapLogURI($map);
		
		$imageURL = $mapuri.'/' . $type .'.png';
		
		//Convert Oacis to RRS-Oacis image
		while (ob_get_level()) {
			ob_end_clean();
		}
		$ch = curl_init();
		$callback = function (callable $callback) {
			return function ($ch, $response) use ($callback) {
				$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				if (!in_array($http_code, [301, 302, 303, 307], true)) {
					$callback($http_code, $response);
				}
				return strlen($response);
			};
		};
		
		curl_setopt_array($ch, [
				CURLOPT_URL => $imageURL,
				CURLOPT_HEADERFUNCTION => $callback(function ($http_code, $header) {
					header($header, false, $http_code);
				}),
				CURLOPT_WRITEFUNCTION => $callback(function ($http_code, $content) use (&$written) {
					$written = true;
					echo $content;
					flush();
				}),
				CURLOPT_FOLLOWLOCATION => true,
				]);
		if (!curl_exec($ch) && !$written) {
			header_remove();
			header('Content-Type: text/plain', true, 400);
			echo 'Bad Request for Simple PHP Proxy: ' . curl_error($ch);
		}
	}*/
	
	
	
}