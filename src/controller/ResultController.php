<?php
namespace adf\controller;

use adf\controller\AbstractController;
use adf\model\ResultGeneration;
use adf\model\ResultTeam;
use adf\model\ResultHelper;
use adf\model\Result2016;
use adf\model\ResultExcel;

class ResultController extends AbstractController{
  
	public function anyIndex($param= null){
		return self::get($param);
	}
	
	public function get($param = null){
		
		//TODO Access the oasis and bring the results
		$simulatorID = $param;
		
		
		//include (Config::$SRC_REAL_URL . 'view/SettingView.php');
		$teams =[];
		
		$maps = Result2016::getMaps();
		
		if($simulatorID=='test'){
			
			//Test
			$teams = Result2016::getTeams();
			
			
		}else{
			
			$parameterSets= ResultHelper::getParameterSets($simulatorID);
			
			$maps = ResultHelper::getMaps($parameterSets);
			
			$teams = ResultHelper::getTeams($simulatorID, $parameterSets);
			
		}
		
		ResultHelper::calPoints($teams);
		
		ResultHelper::addRank($teams);
		
		//echo ResultGeneration::generateHTML('2018', $maps, $teams, '592fe0a36653ff00f53567c2',null);
		
		return ResultGeneration::generateHTML('2018',$simulatorID,$maps, $teams);
  	
  }
  
  public function downloadHTML($param = null){
  
  	$simulatorID = $param;
  	
  	//include (Config::$SRC_REAL_URL . 'view/SettingView.php');
  	$teams =[];
  	
  	$maps = Result2016::getMaps();
  	
  	if($simulatorID=='test'){
  		
  		//Test
  		$teams = Result2016::getTeams();
  		
  		
  	}else{
  		
  		$parameterSets= ResultHelper::getParameterSets($simulatorID);
  		
  		$maps = ResultHelper::getMaps($parameterSets);
  		
  		$teams = ResultHelper::getTeams($simulatorID, $parameterSets);
  		
  	}
  	
  	ResultHelper::calPoints($teams);
  	
  	ResultHelper::addRank($teams);
  	
  	//echo ResultGeneration::generateHTML('2018', $maps, $teams, '592fe0a36653ff00f53567c2',null);
  	
  	return ResultGeneration::generateHTML('2018',$simulatorID,$maps, $teams,null,null,true);
  	
  }
  
}