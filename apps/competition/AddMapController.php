<?php

namespace adf\apps\competition;

use adf\Config;
use adf\controller\AbstractController;

class AddMapController extends AbstractController
{
	public function post()
    {
		$sessionName = $_POST['parameter_session'];
        $mapName = $_POST['parameter_map'];

		SessionManager::addMap($sessionName, $mapName);
		
		//$output = shell_exec("sh ". Config::$ROUTER_PATH. "ruby/add_agent.sh ".$simulatorID." ".$hostID." ".$mapName." " . $agentName);
		
        header('location: '.Config::$TOP_PATH.'competition');
	}
}