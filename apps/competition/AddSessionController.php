<?php

namespace adf\apps\competition;

use adf\Config;
use adf\controller\AbstractController;

class AddSessionController extends AbstractController
{
	public function post()
    {
		$alias = $_POST['parameter_name'];
		$agentsText = $_POST['parameter_agents'];
        $precursor = $_POST['parameter_precursor'];

        $agents = explode(',', $agentsText);

		SessionManager::addSession($alias, $agents, $precursor);
		
		//$output = shell_exec("sh ". Config::$ROUTER_PATH. "ruby/add_agent.sh ".$simulatorID." ".$hostID." ".$mapName." " . $agentName);
		
        header('location: '.Config::$TOP_PATH.'competition');
	}
}