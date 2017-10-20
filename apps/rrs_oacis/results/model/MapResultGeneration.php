<?php
namespace rrsoacis\apps\rrs_oacis\results\model;

use rrsoacis\apps\rrs_oacis\results\model\ResultTeam;
use rrsoacis\system\Config;
use rrsoacis\apps\rrs_oacis\results\ResultMapDownloadController;


class MapResultGeneration{
	
	public static function generateHTML($year,$simulatorID,$mapName,array $teamResult,array $step){
		
		$html = '';
		
		$html .= self::getHeader($year);
		
		$html .= '<body>';
		
		$html .= '<h1>Results for '. $mapName.'</h1>';

		//MapdownloadLink
		$html .= self::getDownloadLinck($simulatorID, $teamResult, $mapName);
		
		$html .= self::getInitMap($simulatorID, $teamResult, $mapName);
		
		$html .= '<br clear="all" />' . "\n";
		$html .= '<br />' . "\n";
		
		
		$html .= self::getTableHeader($simulatorID,$step);
		
		$html .= self::getTableMain($simulatorID,$teamResult,$mapName,$step);
		
		$html .= '</table>'."\n";
		
		
		$html .= '</body>'."\n";
		
		$html .= '<br>'."\n";
		$html .= '<br>'."\n";
		
		$html .= '</html>';
		
		return $html;
		
	}
	
	private function getHeader($year){
		
		return 	'<?xml version="1.0" encoding="iso-8859-1"?>'."\n".
				'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'."\n".
               	'<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">'."\n".
					
               	'<head>'."\n".
               	'<title>Results for map Kobe1</title>'."\n".
               	'<meta http-equiv="refresh" content="15" >'."\n".
               	'<style type="text/css">'."\n".
               	'  body { font-family: sans-serif; }'."\n".
               	"\n".
               	'  table { border-collapse: collapse; }'."\n".
               	'  .running { color:red; }'."\n".
               	'  tr.first { background-color: #E9D44A; }'."\n".
               	'  tr.second { background-color: #C8C8C8; }'."\n".
               	'  tr.third { background-color: #C89D4C; }'."\n".
               	'  div.init-map {  float:left;'."\n".
               	'                  text-align:center; }'."\n".
               	'</style>'."\n".
               	'</head>'."\n";
		
	}

	private function getDownloadLinck($simulatorID,$teamResult,$mapName){

		$downloadLinck = '';


		foreach($teamResult as $name => $value){

			$map_size = strlen(ResultMapDownloadController::downloadMap($simulatorID,$mapName))/1000.0;


			$downloadLinck .= '<div>';
			if($value->isDownload()){

				$downloadURL = './map.tar.gz';

				$downloadLinck .= '<a href="'.$downloadURL.'" >Download map</a>';

			
			}else{


				$downloadLinck .= '<a href="'.Config::$RESOURCE_PATH.'results-map_download/'.$simulatorID.'/'.$mapName.'.tar.gz" >Download map</a>';

			}
			$downloadLinck .= ' (Size: '. $map_size .'KB)';
			$downloadLinck .= '</div>';
			break;

		}


		return $downloadLinck;


	}
	
	private function getInitMap($simulatorID,$teamResult,$mapName){
		
		$initMap = '';
		
		foreach($teamResult as $name => $value){
			
			//$mapurl = Config::$RESOURCE_PATH.'result_map/images/'.$simulatorID.'/'.$mapName.'/'.$name.'/snapshot-init';
			
			$mapurl = $value->getMapLogURI($mapName).'/snapshot-init.png';
			
			if($value->isDownload()){
				$mapurl = './'.$name.'/'.Config::MAP_LOG . '/snapshot-init.png';
			}
			
			
			$initMap .='<div class="init-map">';
			
			$initMap .='<a href="'.$mapurl.'">';
			$initMap .='<img src="'.$mapurl.'" width="400" height="300" alt="Initial situation for '.$mapName.'" />';
			$initMap .='</a>'; 
			
			$initMap .='<br />';
			$initMap .='Initial score: '.$value->getMapInitScores($mapName).' ';
			
			$initMap .='</div>';
			
			break;
		}
		
		return $initMap;
		
	}
	
	private function getTableHeader($simulatorID,$step){
		
		$head = '';
		
		$head .= '<table border="2" cellspacing="0" cellpadding="5">';
		
		$head .= '<tr ><th>Team</th><th>Score</th><th>Points</th>';
		
		
		for($i=0;$i<count($step);$i++){
			$head .= '<th>'.$step[$i].'</th>';
		}
		
		//Final
		$head .= '<th>Final</th>';

		$head .= '<th>Logfile</th></tr>';
		
		return $head;
		
	}
	
	private function getTableMain($simulatorID,$teamResult,$mapName,$step){
		
		
		$main = '';
		
		foreach($teamResult as $name => $value){
			
			$mapurl = $value->getMapLogURI($mapName);
			
			//$mapurl = Config::$RESOURCE_PATH.'result_map/images/'.$simulatorID.'/'.$mapName.'/'.$name;
			
			if($value->isDownload()){
				$mapurl = './'.$name.'/'.Config::MAP_LOG;
			}
			
			//$main.= $mapurl;
			
			$rankClass = '';
			$rank = $value->getRank();
			if($rank==1){
				$rankClass = 'first';
			}else if($rank==2){
				$rankClass = 'second';
				
			}else if($rank==3){
				$rankClass = 'third';
			}
			
			$main .= '<tr class="'.$rankClass.'">'. "\n";
			
			$main .= '<td>'.$name.'</td>';
			
			$score = $value->getScore($mapName);
			
			$rawMapScores = $value->getMapScores($mapName);
			
			$mapScores = explode(' ',$rawMapScores);
			
			$main .= '<td>'.$score['score'].'</td><td>'.$score['points'].'</td>';
			
			$scores = $step;//['50','100','150','200','250','300'];
			
			for($i=0;$i<count($scores);$i++){
				$URL_S = $mapurl.'/snapshot-'.$scores[$i].'.png';
				$main .='<td><a href="'.$URL_S.'">';
				if($score['score']!='')$main .='<img src="'.$URL_S.'" width="100" height="75" alt="Map at turn '.$scores[$i].'" />';
				if($score['score']!='')$main .='</a><br />'.round($mapScores[$scores[$i]-1],4).'</td>';
				else $main .='</td>';
			}

			$URL_S = $mapurl.'/snapshot-final.png';
                        $main .='<td><a href="'.$URL_S.'">';
                        if($score['score']!='')$main .='<img src="'.$URL_S.'" width="100" height="75" alt="Map at turn Final" />';
                        if($score['score']!='')$main .='</a><br />'.round($mapScores[count($mapScores)-1],4).'</td>';
			else $main .='</td>';
			

			//LogFile Link
            		$log_link = str_replace('/'.Config::MAP_LOG,'',$value->getMapLogURI($mapName));
            		if($score['score']!='')$main .= '<td><a href="'.$log_link.'.tar.bz2">Download</a></td>';
			else $main .= '<td></td>';

			$main .= '</tr">'. "\n";
			
		}
		
		return $main;
		
	}
	
	
	
	
	
}
