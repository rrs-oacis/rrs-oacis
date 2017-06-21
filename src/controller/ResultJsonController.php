<?php
namespace adf\controller;

use adf\controller\AbstractController;
use adf\model\ResultGeneration;
use adf\model\ResultTeam;
use adf\model\ResultHelper;

class ResultJsonController extends AbstractController{
	
	public function anyIndex($param= null){
		self::get($param);
	}
	
	public function get($param = null){
		
		//TODO Access the oasis and bring the results
		$simulatorID = $param;
		
		//include (Config::$SRC_REAL_URL . 'view/SettingView.php');
		$testScore =[];
		
		$maps = ['hoge','aa'];
		
		if($simulatorID=='test'){
			
			//Test
			
			//Test data1
			$ait = new ResultTeam('AIT');
			$testScore['AIT'] = $ait;
			$ait->addMapResult('hoge', 200, 300);
			$ait->addMapResult('aa', 120, 23);
			$ait->setRank(1);
			$ait->setLogLink("https://www.yahoo.co.jp/");
			$ait->setColorType(3);
			
			//Test data2
			$hoge = new ResultTeam('hoge');
			$testScore['hoge'] = $hoge;
			$hoge->addMapResult('hoge', 210, 100);
			$hoge->addMapResult('aa', 100, 200);
			$hoge->setRank(1);
			$hoge->setLogLink("https://www.yahoo.co.jp/");
			$hoge->setColorType(0);
		}else{
			
			$parameterSets= ResultHelper::getParameterSets($simulatorID);
			
			$maps = ResultHelper::getMaps($parameterSets);
			
			$testScore = ResultHelper::getTeams($simulatorID, $parameterSets);
			
		}
		
		
		echo json_encode(array_values($testScore));
		
		
	}
	
}