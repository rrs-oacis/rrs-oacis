<?php
namespace adf\model;

use \adf\model\ResultTeam;
use \adf\model\MapScoreTest;
use adf\Config;

class ResultHelper{
	
	public static function getParameterSets(String $simulatorID)
    {
		
		$rawData = file_get_contents('http://127.0.0.1:3000/simulators/'.$simulatorID . '.json');
		
		$json = json_decode($rawData);
		
		return $json->parameter_sets;
		
	}
	
	public static function getMaps(array $parameterSets){
		
		
		$maps = [];
		
		for ($i = 0 ; $i < count($parameterSets); $i++) {
			
			$m = explode("_", $parameterSets[$i]->v->MAP)[0];
			
			$maps[] = $m;
			
		}
		
		
		return array_merge(array_unique($maps));
		
	}
	
	public static function getTeams(String $simulatorID ,array $parameterSets,$download = false){
		
		$teams = [];
		
		for ($i = 0 ; $i < count($parameterSets); $i++) {
			
			$m = explode("_", $parameterSets[$i]->v->MAP)[0];
			
			$teamName = explode("_", $parameterSets[$i]->v->A)[0];
			
			$team;
			
			if(! isset($teams[$teamName])){
				$teams[$teamName] = new ResultTeam($teamName,$download);
			}
			
			$team = $teams[$teamName];
			
			//Score
			$parameterID = $parameterSets[$i]->id;
			$runs = self::getRuns($parameterID);
			
			//Fast 1
			$runID = $runs[0]->id;
			
			$run = self::getRun($runID);
			
			$score = self::getFinalScore($simulatorID, $parameterID, $runID);
			
			$team->addMapResult($m, $score, 100);
			
			$team->addMapLogURI($m, self::getMapImageURI($simulatorID, $parameterID, $runID));
			
			$team->addMapScores($m, self::getScores($simulatorID, $parameterID, $runID));
			
			$team->addMapInitScores($m, self::getInitScore($simulatorID, $parameterID, $runID));
			
			$team->addMapStep($m, self::getFinalStep($simulatorID, $parameterID, $runID));
			
			//echo self::getMapStep($simulatorID, $parameterID, $runID);
			
		}
		
		$key_id = [];
		
		foreach ($teams as $key => $value){
			$key_id[$key] = $value->getTotalScore()['point'];
		}
		array_multisort ( $key_id , SORT_DESC, $teams);
		
		$rank = 1;
		foreach ($teams as $key => $value){
			$value->setRank($rank);
			$rank++;
		}
		
		return $teams;
		
	}
	
	public static function getRuns(String $parameterSetID){
		
		$rawData = file_get_contents('http://127.0.0.1:3000/parameter_sets/'.$parameterSetID. '.json');
		
		$json = json_decode($rawData);
		
		return $json->runs;
		
	}
	
	public static function getRun(String $runID){
		
		$rawData = file_get_contents('http://127.0.0.1:3000/runs/'.$runID. '.json');
		
		$json = json_decode($rawData);
		
		return $json->runs;
		
	}

	public static function getMapImageURI($simulatorID, $parameterSetID, $runID){
		
		return 'http://127.0.0.1:3000/Result_development/'.$simulatorID.'/'.$parameterSetID.'/'.$runID.'/'.Config::MAP_LOG;
		
	}

    public static function getScores($simulatorID, $parameterSetID, $runID)
    {
        $rawData = @file_get_contents('http://127.0.0.1:3000/Result_development/'.$simulatorID.'/'.$parameterSetID.'/'.$runID.'/'.Config::MAP_LOG.'/scores.txt');
        $score = round((0 + $rawData), 2);
        return $score;
    }

    public static function getfinalScore($simulatorID, $parameterSetID, $runID)
    {
        $rawData = @file_get_contents('http://127.0.0.1:3000/Result_development/'.$simulatorID.'/'.$parameterSetID.'/'.$runID.'/'.Config::MAP_LOG.'/final-score.txt');
        $score = round((0 + $rawData), 2);
        return $score;
    }

	public static function getInitScore($simulatorID, $parameterSetID, $runID)
    {
		$rawData = @file_get_contents('http://127.0.0.1:3000/Result_development/'.$simulatorID.'/'.$parameterSetID.'/'.$runID.'/'.Config::MAP_LOG.'/init-score.txt');
        $score = round((0 + $rawData), 2);
        return $score;
	}
	
	public static function getFinalStep($simulatorID, $parameterSetID, $runID)
    {
		//get map step file @see step_image_count.sh
		$rawData = @file_get_contents('http://127.0.0.1:3000/Result_development/'.$simulatorID.'/'.$parameterSetID.'/'.$runID.'/'.Config::MAP_LOG.'/count.txt');
		
		return $rawData;
	}

	public static function getMapStep4Teams($teams,$maps){
		foreach ($teams as $key => $value){
			return intval($value->getMapStep($maps));
		}
	}
	
	public static function getMapStepArray($stepSize){
		
		$a = [];
		
		for($i=1;$i<=$stepSize;$i++){
			$a[] = $i *50;
		}
		
		return $a;
		
	}
	
	public static function addRank(array $teams){
		
		//Add rank
		$key_id = [];
		
		$teams2 = $teams;
		foreach ($teams2 as $key => $value){
			$key_id[$key] = $value->getTotalScore()['points'];
		}
		array_multisort ( $key_id , SORT_DESC, $teams2);
		
		$rank = 1;
		foreach ($teams2 as $key => $value){
			
			$teams[$key]->setRank($rank);
			$rank++;
		}
		
	}
	
	
	
	//TODO 
	public static function calPoints(array $teams){
		
		//TODO 
		
		$SMkj = [];
		
		$maps = [];
		
		$MAX = [];
		
		$MIN = [];
		
		$AVERAGE = [];
		
		$SM = [];
		
		
		$TEAM_SIZE = 0;
		
		$STEP_SIZE = 0;
		
		$MSS= [];
		
		$MAP_Scores = [];
		
		foreach ($teams as $key => $value){
			$maps = $value->getMaps();
			$TEAM_SIZE++;
		}
		
		$SDC = 2;
		
		$STEP_SIZE = $TEAM_SIZE * $SDC;
		
		$MAX_SCORE = $STEP_SIZE;
		
		//$SDC = 4;
		//(2)
		//$MSkj = $SDC * 3;
		
		foreach ($maps as $key => $value){
			
			foreach ($teams as $key1 => $value1){
				
				$score = $value1->getScore($key);
				
				if($MAX[$key]<$score['score']){
					$MAX[$key] = $score['score'];
				}
			
				
				if(!isset($MAP_Scores[$key])){
					$MAP_Scores[$key] = [];
				}
				
				$MAP_Scores[$key][] = $score['score'];
				
			}
			
			$MIN[$key] = min($MAP_Scores[$key]);
			
			
			$AVERAGE[$key]= array_sum($MAP_Scores[$key]) / count($MAP_Scores[$key]);
		
			//$SM[$key] = $MAX[$key]- ($MAX[$key]- $AVERAGE[$key])
			
			//(1)
			$SMkj[$key] = $MAX[$key] - (($MAX[$key]-$AVERAGE[$key])*2 );
			
			$STEP[$key] = [];
			
			$MSS[$key] = [];
			
			$MSS[$key][] = 0;
			
			
			
			//echo $MAX[$key] . ': ' .$MIN[$key].' : '.$MAX_SCORE." ; ";
			
			for($i=1; $i<=$STEP_SIZE;$i++){
				
				//(3)
				//$zore = ($MSkj*($MSkj-$i));
				//if($zore==0)$zore=1;
				
				//echo $MSkj . ' : ' . $i .'<br>';
				
				//$mss = ($MAX[$key]-$SMkj[$key])/$zore;
				
				//$MSS[$key][] = $mss;
				
				$mss1 = $MAX[$key]- ( ($MAX[$key]-$SMkj[$key]) / $MAX_SCORE * ($MAX_SCORE- $i));
				
				$MSS[$key][] = $mss1;
				
				//var_dump($SMkj[$key]);
				
				
			}
			
			
			//echo $key . ' : ' . $MSS[$key][15] . '<br>';
			
			rsort($STEP[$key]);
			
			$key_id2 = [];
			
			$teams2 = $teams;
			foreach ($teams2 as $te2=> $te2value){
				$key_id2[$te2] = $te2value->getScore($key)['score'];
			}
			array_multisort ( $key_id2 , SORT_DESC, $teams2);
			
			$old_point = [];
			
			//echo '<br>';
			
			foreach ($teams2 as $key1 => $value1){
				
				//echo $key1 .' ';
				
				$point = -1;
				
				$tScore = $value1->getScore($key)['score'];
				
				//echo $tScore . '<br>';
				
				if($MSS[$key][1] > $tScore){
					$point = 1;
				}
				
				
				
				for($i=1; $i<=$STEP_SIZE;$i++){
					
					if($point==-1 && $i==$STEP_SIZE){
						//(5)
						$point = $STEP_SIZE; 
					}else if($MSS[$key][$i] < $tScore && $tScore < $MSS[$key][$i+1]){
						//(4)
						
						for($i1 = $i;$i1>=1;$i1--){
							if(!isset($old_point[$i1])){
								$point = $i1;
								$old_point[$i1] = true;
								break;
							}
						}
						/*
						if(isset($old_point[$i])){
							$point = $i-1;
							$old_point[$i-1] = true;
						}else{
							$point = $i;
							$old_point[$i] = true;
							echo isset($old_point[$i]);
						}*/
						
						
					}
					
				}
				
				if($tScore==0){
					$point = 1;
				}
				
				//echo $key . ' ' .$key1. " ! <br>";
				
				//for($i=1; $i<=$STEP_SIZE;$i++){
				//	echo $MSS[$key][$i] . " | ";
				//}
				
				//var_dump($MSS[$key]);
				//echo '<br> <br>';
				
				//$point = $STEP[$key][$value1->getRank()-1];
				
				$point = round($point,2);
				
				//echo $key . " : " . $point;
				
				//var_dump($STEP[$key]);
				
				$teams[$key1]->setPoint($key,$point);
				//$value1->setPoint($key,$point);
				
			}
			
			
			
		}
		
		
				
		
	}
	
	
}