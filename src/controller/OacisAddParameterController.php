<?php

namespace adf\controller;

use adf\Config;
use adf\controller\AbstractController;

class OacisAddParameterController extends AbstractController {
	
	public function post() {
		
		ini_set ( 'display_errors', 1 );
		
		//TODO オアシスに登録処理
		
		$simulatorID = "590463aee4dec200d962035a";
		
		if($_POST['parameter_simulator_id']!=""){
			$simulatorID = $_POST['parameter_simulator_id'];
			
		}
		
		$name = $_POST['parameter_name'];
		
		$mapName = $_POST['parameter_map'];
		$agentName = $_POST['parameter_agent'];
		
		$output = shell_exec("sh ". Config::$ROUTER_PATH. "ruby/add_agent.sh ".$simulatorID." ".$mapName." " . $agentName);
		
		echo '{"output":"hoge" }';
		
		
	}
	
	
}