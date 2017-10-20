<?php

namespace rrsoacis\apps\rrs_oacis\manual_post2;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\ClusterManager;

class OacisAddParameterController extends AbstractController {
	
	public function post() {
		
		ini_set ( 'display_errors', 1 );
		
		//TODO オアシスに登録処理
		
		$simulatorID = "590463aee4dec200d962035a";
		$hostID = ClusterManager::getMainHostGroup();
		
		if($_POST['parameter_simulator_id']!=""){
			$simulatorID = $_POST['parameter_simulator_id'];
		}
		

		$mapName = $_POST['parameter_map'];
		$agentName = $_POST['parameter_agent'];
		
		$output = shell_exec("sh ". Config::$ROUTER_PATH. "ruby/add_agent.sh ".$simulatorID." ".$hostID." ".$mapName." " . $agentName);
		
		echo '{"output":"hoge" }';
		
		
	}
	
	
}