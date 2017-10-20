<?php
namespace rrsoacis\apps\rrs_oacis\results\model;

use rrsoacis\apps\rrs_oacis\results\model\ResultTeam;

class ResultExcel{
	
	public static function getMaps(){
		
		return ['Kobe4'];
		
	}
	
	public static function getTeams(){
		
		$teams =[];
		
		//Test data1
		$ait = new ResultTeam('GUC-ArtSapience');
		$teams['GUC-ArtSapience'] = $ait;
		$ait->addMapResult('Kobe4', 169.57, 0);
		
		$ait = new ResultTeam('MRL');
		$teams['MRL'] = $ait;
		$ait->addMapResult('Kobe4', 170.51, 0);
		
		$ait = new ResultTeam('Poseison');
		$teams['Poseison'] = $ait;
		$ait->addMapResult('Kobe4', 165.62, 0);
		
		$ait = new ResultTeam('S.O.S');
		$teams['S.O.S'] = $ait;
		$ait->addMapResult('Kobe4', 183.2, 0);
		
		return $teams;
		
	}
	
}