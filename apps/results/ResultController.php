<?php
namespace adf\apps\results;

use adf\controller\AbstractController;
use adf\apps\results\model\ResultGeneration;
use adf\apps\results\model\ResultTeam;
use adf\apps\results\model\ResultHelper;
use adf\apps\results\model\Result2016;
use adf\apps\results\model\esultExcel;

class ResultController extends AbstractController{
  
	//public function anyIndex($param= null){
	//	return self::get($param);
	//}

	public function anyIndex($param= null,$preParam= null){
		return self::get($param,$preParam);
	}
	
	public function get($param = null,$preParam= null){
		
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
		
		


		$preSession = null;
		$prePoint = null;
		if($preParam!=null){


			$preSession = $preParam;

			$preParameterSets= ResultHelper::getParameterSets($preParam);
			
			$preMaps = ResultHelper::getMaps($preParameterSets);
			
			$preTeams = ResultHelper::getTeams($preParam, $preParameterSets);

			ResultHelper::calPoints($preTeams);
		
			ResultHelper::addRank($preTeams);

			$prePoint = [];
			foreach($preTeams as $name => $value)
			{
				$prePoint[$name] = $value->getTotalScore()['points'];


				$teams[$name]->addPresentation($prePoint[$name]);

			}

		}

		ResultHelper::addRank($teams);
		
		//echo ResultGeneration::generateHTML('2018', $maps, $teams, '592fe0a36653ff00f53567c2',null);
		
		//return ResultGeneration::generateHTML('2018',$simulatorID,$maps, $teams,null,$prePoint);
		return ResultGeneration::generateHTML('2018',$simulatorID,$maps, $teams,$preSession,null);
  	
  }
  
  public function downloadHTML($param = null,$preParam= null){
  
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

  	$preSession = null;
  	if($preParam!=null){
		$preSession = $preParam;
  	}
  	
  	//echo ResultGeneration::generateHTML('2018', $maps, $teams, '592fe0a36653ff00f53567c2',null);
  	
  	return ResultGeneration::generateHTML('2018',$simulatorID,$maps, $teams,$preSession,null,true);
  	
  }
  
}