<?php
namespace adf\apps\results;

use adf\controller\AbstractController;
use adf\apps\results\model\ResultGeneration;
use adf\apps\results\model\ResultTeam;
use adf\apps\results\model\ResultHelper;
use adf\apps\results\model\Result2016;
use adf\apps\results\model\esultExcel;
use adf\apps\competition\SessionManager;

class ResultController extends AbstractController{
  
	public function anyIndex($param= null,$preParam= null){
		return self::get($param,$preParam);
	}
	
	public function get($param = null,$preParam= null){
		error_reporting(0);
		
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
		
		//ResultHelper::calPoints($teams);
		

		$data = SessionManager::getSessions();

		for($i=0;$i<count($data);$i++){

			if($data[$i]["alias"]==$preParam){
				$preParam = $data[$i]["name"];
				break;
			}

		}



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


				if(isset($teams[$name]))$teams[$name]->addPreSession($prePoint[$name]);


			}

		}


		//TODO presentation function
		$sessionName = SessionManager::getSession($param)['alias'];

		$presentationDatas = SessionManager::getPresentations();

		
		$presentation = [];

		if(isset($presentationDatas[$sessionName])){
			$presentation = $presentationDatas[$sessionName];
		}


		$presentationTeam = [];

		foreach ($presentation as $key => $value){
			
			//Add point
			//$teams[$key]->addPresentation($value);
			//if(isset($teams[$key]))$teams[$key]->addMapResult('Presentation',$value,1);
			$presentationTeam[$key] = new ResultTeam($key);
			$presentationTeam[$key]->addMapResult('Presentation',$value,1);

		}


		//Fix
		if(count($presentation)>0){
			$maps[] = 'Presentation';
		}
		
                ResultHelper::calPoints($teams);

		if(count($presentationTeam)>0){
			ResultHelper::calPoints($presentationTeam);
		}
		
		foreach ($presentationTeam as $key => $value){
			if(isset($teams[$key]))$teams[$key]->addMapResult('Presentation', $value->getTotalScore()['score'], $value->getTotalScore()['points']);
		}


		ResultHelper::addRank($teams);
		
		//Fix 0729
		ResultHelper::setResultColor($teams, SessionManager::getSession($param)['highlight']);

		//echo ResultGeneration::generateHTML('2018', $maps, $teams, '592fe0a36653ff00f53567c2',null);
		
		//return ResultGeneration::generateHTML('2018',$simulatorID,$maps, $teams,null,$prePoint);
		return ResultGeneration::generateHTML('2017',$simulatorID,$maps, $teams,$preSession,null);
  	
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
  	


  		$data = SessionManager::getSessions();

		for($i=0;$i<count($data);$i++){

			if($data[$i]["alias"]==$preParam){
				$preParam = $data[$i]["name"];
				break;
			}

		}
  	
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

				$teams[$name]->addPreSession($prePoint[$name]);
			}

		}

		//TODO OK, presentation function
		$sessionName = SessionManager::getSession($param)['name'];

		$presentationDatas = SessionManager::getPresentations();

		
		$presentation = [];

		if(isset($presentationDatas[$sessionName])){
			$presentation = $presentationDatas[$sessionName];
		}


		foreach ($presentation as $key => $value){
			
			//Add point
		        $teams[$key]->addMapResult('Presentation',$value,1);

			//$teams[$key]->addPresentation($value);

		}

		//Fix
                if(count($presentation)>0){
                        $maps[] = 'Presentation';
                }


                ResultHelper::calPoints($teams);

		ResultHelper::addRank($teams);
  	
  		//echo ResultGeneration::generateHTML('2018', $maps, $teams, '592fe0a36653ff00f53567c2',null);
  	
  		return ResultGeneration::generateHTML('2017',$simulatorID,$maps, $teams,$preSession,null,true);
  	
  	}
  
}


