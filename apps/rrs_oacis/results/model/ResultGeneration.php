<?php
namespace rrsoacis\apps\rrs_oacis\results\model;

use rrsoacis\apps\rrs_oacis\results\model\ResultTeam;
use rrsoacis\system\Config;
use rrsoacis\apps\rrs_oacis\competition\SessionManager;

class ResultGeneration{
	
	public static function generateHTML($year,$simulatorID,array $mapNames,array $teamResult, $day1ID = null, $presentation = null,$download = false){
		
		$html = '';
		
		//Add Header
		$html .= self::getHeader($year);
		$html .= '<body>';
		$html .= self::getTableHeader($simulatorID, $mapNames, $day1ID, $download);
		$html .= self::getTableMain($mapNames, $teamResult, $day1ID, $presentation);
		$html .= '</table>'."\n";

		/*
		if (!$download)
		{
			$html .= '<a href="'.Config::$RESOURCE_PATH.'/result_download/'.$simulatorID.'">Download</a>';
		}
		*/
		
		$html .= '</body>'."\n";
		$html .= '</html>';
		
		return $html;
		
	}
	
	private function getHeader($year){
		
		return 	'<?xml version="1.0" encoding="iso-8859-1"?>'. "\n".
				'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> '. "\n".
				'<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en"> '. "\n".
				'<head> '. "\n".
				'<title>Robocup ' .$year .' Rescue Simulation League Results</title> '. "\n".
				''. "\n".
				'<style type="text/css"> '. "\n".
				'	body { font-family: sans-serif; }  '. "\n".
				'	table { border-collapse: collapse; margin-bottom:1em }  '. "\n".
				'   tr.first { background-color: #E9D44A; }  '. "\n".
				'	tr.second { background-color: #C8C8C8; }  '. "\n".
				'	tr.third { background-color: #C89D4C; }  '. "\n".
				'	tr.qualified { background-color: #AABBFF; }  '. "\n".
				'	div.init-map {  '. "\n".
				'		float:left;  '. "\n".
				'		text-align:center;  '. "\n".
				'	}  '. "\n".
				'	td { white-space: nowrap; }  '. "\n".
				'</style>  '. "\n".
				' '. "\n".
				'</head>  '. "\n";
		
	}
	
	private function getTableHeader($simulatorID,array $mapNames, $day1ID = null,$download = false){
	
		$sessionName =  SessionManager::getSession($simulatorID)['alias'];
	
		$head = '';
		$head .= 
			'<table border="2" cellspacing="0" cellpadding="5">  '. "\n".
			'<tr>'."\n".'  <th rowspan="2">'.$sessionName.'</th>  '. "\n";
		
		for ($i = 0 ; $i < count($mapNames); $i++)
		{
			$mapName= $mapNames[$i];
			
			if(!$download){
				//Fix
				if($mapName!='Presentation'){
				$head .= 
				'  <th colspan="2"><a target="_blank" href="'.Config::$RESOURCE_PATH.'results-map/'.$simulatorID.'/'.$mapName.'">'.$mapName.'</a></th>  '. "\n";
				}else{
					$head .= '<th colspan="2">'.$mapName . '</th>';
				}

			}else{
				$head .=
				'  <th colspan="2"><a target="_blank" href="./results_map/'.$simulatorID.'/'.$mapName.'/index.html">'.$mapName.'</a></th>  '. "\n";
			}
		}
		
		//TODO oldDay
		if($day1ID!=null){
			$head .=
			//'  <th colspan="2">day1</th>  '. "\n";
			'  <th colspan="1">day1</th>  '. "\n";
		}
		
		//Total
		$head .= '  <th colspan="2">Total</th>  '. "\n";
		
		$head .= '  <th rowspan="2">Rank</th>'."\n";

		if ($download)
        {
            $head .= '<th rowspan="2">Agent Logs</th>'. "\n";
        }

		$head .= '</tr>'. "\n";
		
		$head .= '<tr>'. "\n";
		
		//+1　Total
		$size = 1;
		if ($day1ID!=null) { $size = 2; }
		for ($i = 0 ; $i < count($mapNames) +$size; $i++)
		{
			if ($day1ID!=null && count($mapNames) == $i) { 
				$head .= '  <th>Points</th>'."\n"; 
			}else{
				$head .= '  <th>Score</th><th>Points</th>'."\n";
			}

		}
		
		
		$head .= '</tr>';
		
		return $head;
		
		
	}
	
	private function getTableMain(array $mapNames,array $teamResult, $day1ID = null,array $presentation = null, $download = false)
    {
		$main = '';
	
		foreach($teamResult as $name => $value)
		{
			//Add $presentation
			if($presentation != null)
			{
				$value->addPresentation($presentation[$name]);
			}
			
			$colorClass = "";
			switch ($value->getColorType())
            {
				case 0: $colorClass = '';break;
				case 1: $colorClass = 'qualified';break;
				case 2: $colorClass = 'second';break;
				case 3: $colorClass = 'first';break;
				case 4: $colorClass = 'third';break;
			}
			
			$main .= '<tr class="'.$colorClass.'">'."\n";
			$main .= '  <td>'.$value->getTeamName().'</td>'."\n";
			
			for ($i = 0 ; $i < count($mapNames); $i++)
			{
				
				$mapName= $mapNames[$i];
				$result = $value->getScore($mapName);
				$score = isset($result['score']) ? $result['score'] : 'none';
				$points = isset($result['points']) ? $result['points'] : 'none';
				
				$main .= '  <td>'.$score.'</td>'.'<td>'.$points.'</td>'."\n";
				
			}
			
			if ($day1ID != null)
			{
				
				$day1parameterSets= ResultHelper::getParameterSets($day1ID);
				
				$day1maps = ResultHelper::getMaps($day1parameterSets);
				
				$day1teams = ResultHelper::getTeams($day1ID, $day1parameterSets);
				
				ResultHelper::calPoints($day1teams);
				
				$day1team = $day1teams[$name];
				
				$result = $day1team->getTotalScore();
				$day1Score = isset($result['score']) ? $result['score'] : 'none';
				$day1point = isset($result['points']) ? $result['points'] : 'none';
				
				//$main .= '  <td>'.$score.'</td>'.'<td>'.$points.'</td>'."\n";
				$main .= '  <td>'.$day1point.'</td>'."\n";
				
				//Total
				$total = $value->getTotalScore();
				$tS= ($total['score']+ ($day1Score=='none' ? 0:$day1Score) );
				//$tP= ($total['points']+($day1point=='none' ? 0:$day1point));
				$tP= $total['points'];
				
				$main .= '  <td>'.$tS.'</td>'.'<td>'.$tP.'</td>'."\n";
				//$main .= '  <td>'.$tP.'</td>'."\n";
				
			}
			else
            {
				//Total
				$total = $value->getTotalScore();
				$main .= '  <td>'.$total['score'].'</td>'.'<td>'.$total['points'].'</td>'."\n";
				
			}
			
			
			
			//Rnak
			$main .= '  <td>'.$value->getRank().'</td>'."\n";
			
			//Log
            if ($download)
            {
                $main .=  '  <td><a target="_blank" href="'.$value->getLogLink().'">Download</a></td>'."\n";
            }

			$main .= '</tr>'."\n";
			
			
		}
		
		return $main;
		
		
	}
	
	public static function generateSimpleHTML($year,array $mapNames,array $teamResult){
		
		$html = '';
		
		//Add Header
		$html .= self::getHeader($year);
		
		$html .= '<body>';
		
		$html .= self::getSimpleTableHeader($mapNames);
		
		$html .= self::getSimpleTableMain($mapNames, $teamResult);
		
		$html .= '</table>'."\n";
		
		$html .= '</body>'."\n";
		$html .= '</html>';
		
		return $html;
		
	}
	
	private function getSimpleTableHeader(array $mapNames){
		
		$head = '';
		$head .=
		'<table border="2" cellspacing="0" cellpadding="5">  '. "\n".
		'<tr>'."\n".'  <th rowspan="2">Team</th>  '. "\n";
		
		for ($i = 0 ; $i < count($mapNames); $i++) {
			
			$mapName= $mapNames[$i];
			
			$head .=
			'  <th>'.$mapName.'</th>  '. "\n";
			
		}
		
		
		//Total
		$head .=
		'  <th>Total</th>  '. "\n";
		
		$head .=
		'  <th rowspan="2">Rank</th>'. "\n";
		
		$head .= '</tr>'. "\n";
		
		$head .= '<tr>'. "\n";
		
		//+1　Total
		$size = 1;
		for ($i = 0 ; $i < count($mapNames) +$size; $i++) {
			
			$head .=
			'  <th>Score</th>'."\n";
			
		}
		
		$head .= '</tr>';
		
		return $head;
		
		
	}
	
	private function getSimpleTableMain(array $mapNames,array $teamResult){
		
		$main = '';
		
		foreach($teamResult as $name => $value){
			
			$colorClass = "";
			switch($value->getColorType()){
				case 0: $colorClass = '';break;
				case 1: $colorClass = 'qualified';break;
				case 2: $colorClass = 'second';break;
				case 3: $colorClass = 'first';break;
			}
			
			$main .= '<tr class="'.$colorClass.'">'."\n";
			$main .= '  <td>'.$value->getTeamName().'</td>'."\n";
			
			for ($i = 0 ; $i < count($mapNames); $i++) {
				
				$mapName= $mapNames[$i];
				$result = $value->getScore($mapName);
				$score = isset($result['score']) ? $result['score'] : 'none';
				
				$main .= '  <td>'.$score.'</td>'."\n";
				
			}
			
			//Total
			$total = $value->getTotalScore();
			$main .= '  <td>'.$total['score'].'</td>'."\n";
			
			//Rnak
			$main .= '  <td>'.$value->getRank().'</td>'."\n";
			
			
			$main .= '</tr>'."\n";
			
			
		}
		
		return $main;
		
		
	}
	
}
