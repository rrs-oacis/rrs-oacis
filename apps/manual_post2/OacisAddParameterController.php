<?php

namespace adf\apps\manual_post2;

use adf\Config;
use adf\controller\AbstractController;
use adf\file\ClusterLoader;

class OacisAddParameterController extends AbstractController {
	
	public function post() {
		
		ini_set ( 'display_errors', 1 );
		
		//TODO オアシスに登録処理
		
		$simulatorID = "590463aee4dec200d962035a";
		$hostID = ClusterLoader::getMainHostGroup();
		
		if($_POST['parameter_simulator_id']!=""){
			$simulatorID = $_POST['parameter_simulator_id'];
		}
		

		$mapName = $_POST['parameter_map'];
		$agentName = $_POST['parameter_agent'];
		
		$output = shell_exec("sh ". Config::$ROUTER_PATH. "ruby/add_agent.sh ".$simulatorID." ".$hostID." ".$mapName." " . $agentName);
		
		echo '{"output":"hoge" }';
		
		
	}
	
	
}