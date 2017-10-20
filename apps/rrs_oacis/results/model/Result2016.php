<?php
namespace rrsoacis\apps\rrs_oacis\results\model;

use rrsoacis\apps\rrs_oacis\results\model\ResultTeam;

class Result2016{
	
	public static function getMaps(){
		
		return 
		['NY2','Joao2','Istanbul2','Paris2','Eindhoven2','Kobe3','Mexico2','Berlin2','Presentation'];
		
	}
	
	public static function getTeams(){
		
		$teams =[];
		
		//Test data1
		$ait = new ResultTeam('MRL');
		$teams['MRL'] = $ait;
		$ait->addMapResult('NY2', 158.90, 0);
		$ait->addMapResult('Joao2', 110.94, 0);
		$ait->addMapResult('Istanbul2', 191.55, 0);
		$ait->addMapResult('Paris2', 176.86, 0);
		$ait->addMapResult('Eindhoven2', 363.71, 0);
		$ait->addMapResult('Kobe3', 55.41, 0);
		$ait->addMapResult('Mexico2', 253.81, 0);
		$ait->addMapResult('Berlin2', 184.89, 0);
		$ait->addMapResult('Presentation', 25.75, 0);
		
		$ait = new ResultTeam('Poseidon');
		$teams['Poseidon'] = $ait;
		$ait->addMapResult('NY2', 133.06, 0);
		$ait->addMapResult('Joao2', 100.64, 0);
		$ait->addMapResult('Istanbul2', 163.95, 0);
		$ait->addMapResult('Paris2', 161.21, 0);
		$ait->addMapResult('Eindhoven2', 363.72, 0);
		$ait->addMapResult('Kobe3', 41.40, 0);
		$ait->addMapResult('Mexico2', 199.19, 0);
		$ait->addMapResult('Berlin2', 204.84, 0);
		$ait->addMapResult('Presentation', 22.00, 0);
		
		$ait = new ResultTeam('Kherad');
		$teams['Kherad'] = $ait;
		$ait->addMapResult('NY2', 133.83, 0);
		$ait->addMapResult('Joao2', 54.06, 0);
		$ait->addMapResult('Istanbul2', 177.06, 0);
		$ait->addMapResult('Paris2', 156.12, 0);
		$ait->addMapResult('Eindhoven2', 305.02, 0);
		$ait->addMapResult('Kobe3', 45.16, 0);
		$ait->addMapResult('Mexico2', 0.00, 0);
		$ait->addMapResult('Berlin2', 195.42, 0);
		$ait->addMapResult('Presentation', 0.00, 0);
		
		$ait = new ResultTeam('R.A.S Roshd');
		$teams['R.A.S Roshd'] = $ait;
		$ait->addMapResult('NY2', 146.62, 0);
		$ait->addMapResult('Joao2', 49.02, 0);
		$ait->addMapResult('Istanbul2', 156.26, 0);
		$ait->addMapResult('Paris2', 61.58, 0);
		$ait->addMapResult('Eindhoven2', 287.12, 0);
		$ait->addMapResult('Kobe3', 12.65, 0);
		$ait->addMapResult('Mexico2', 51.14, 0);
		$ait->addMapResult('Berlin2', 151.79, 0);
		$ait->addMapResult('Presentation', 0.00, 0);
		
		$ait = new ResultTeam('CSU-Yunlu');
		$teams['CSU-Yunlu'] = $ait;
		$ait->addMapResult('NY2', 136.09, 0);
		$ait->addMapResult('Joao2', 52.92, 0);
		$ait->addMapResult('Istanbul2', 175.54, 0);
		$ait->addMapResult('Paris2', 161.11, 0);
		$ait->addMapResult('Eindhoven2', 300.05, 0);
		$ait->addMapResult('Kobe3', 35.20, 0);
		$ait->addMapResult('Mexico2', 104.06, 0);
		$ait->addMapResult('Berlin2', 169.69, 0);
		$ait->addMapResult('Presentation', 14.50, 0);
		
		$ait = new ResultTeam('GUC-ArtSapience');
		$teams['GUC-ArtSapience'] = $ait;
		$ait->addMapResult('NY2', 113.52, 0);
		$ait->addMapResult('Joao2', 77.29, 0);
		$ait->addMapResult('Istanbul2', 138.21, 0);
		$ait->addMapResult('Paris2', 66.87, 0);
		$ait->addMapResult('Eindhoven2', 327.70, 0);
		$ait->addMapResult('Kobe3', 9.59, 0);
		$ait->addMapResult('Mexico2', 34.54, 0);
		$ait->addMapResult('Berlin2', 158.38, 0);
		$ait->addMapResult('Presentation', 24.75, 0);
		
		$ait = new ResultTeam('Ri-one');
		$teams['Ri-one'] = $ait;
		$ait->addMapResult('NY2', 106.07, 0);
		$ait->addMapResult('Joao2', 36.52, 0);
		$ait->addMapResult('Istanbul2', 155.31, 0);
		$ait->addMapResult('Paris2', 125.01, 0);
		$ait->addMapResult('Eindhoven2', 288.09, 0);
		$ait->addMapResult('Kobe3', 22.77, 0);
		$ait->addMapResult('Mexico2', 190.60, 0);
		$ait->addMapResult('Berlin2', 129.66, 0);
		$ait->addMapResult('Presentation', 17.25, 0);
		
		$ait = new ResultTeam('SEU-Jolly');
		$teams['SEU-Jolly'] = $ait;
		$ait->addMapResult('NY2', 145.00, 0);
		$ait->addMapResult('Joao2', 54.39, 0);
		$ait->addMapResult('Istanbul2', 167.52, 0);
		$ait->addMapResult('Paris2', 155.24, 0);
		$ait->addMapResult('Eindhoven2', 301.56, 0);
		$ait->addMapResult('Kobe3', 44.99, 0);
		$ait->addMapResult('Mexico2', 128.34, 0);
		$ait->addMapResult('Berlin2', 190.10, 0);
		$ait->addMapResult('Presentation', 17.50, 0);
		
		return $teams;
		
	}
	
}