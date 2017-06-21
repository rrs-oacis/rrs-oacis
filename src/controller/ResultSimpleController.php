<?php
namespace adf\controller;

use adf\controller\AbstractController;
use adf\model\ResultGeneration;
use adf\model\ResultTeam;
use adf\model\ResultHelper;

class ResultSimpleController extends AbstractController{
	
	public function anyIndex($param= null){
		self::get($param);
	}
	
	public function get($param = null){
		
		$simulatorID = $param;
		
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
			
		}else{
			
			$parameterSets= ResultHelper::getParameterSets($simulatorID);
			
			$maps = ResultHelper::getMaps($parameterSets);
			
			$testScore = ResultHelper::getTeams($simulatorID, $parameterSets);
			
			$c = 0;
			foreach ($testScore as $key => $value){
				if($value->getRank()==2)$value->setColorType(2);
				if($value->getRank()==1)$value->setColorType(3);
				$c++;
			}
			
		}
		
		
		echo ResultGeneration::generateSimpleHTML('2018', $maps, $testScore);
		
	}
	
}