<?php

namespace rrsoacis\apps\rrs_oacis\competition;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;

class AddSessionController extends AbstractController
{
	public function post()
    {
		$alias = $_POST['parameter_name'];
        $agents = $_POST['parameter_agents'];
        $precursor = $_POST['parameter_precursor'];
        $highlight = $_POST['parameter_highlight'];


		SessionManager::addSession($alias, $agents, $precursor, $highlight);
		
		//$output = shell_exec("sh ". Config::$ROUTER_PATH. "ruby/add_agent.sh ".$simulatorID." ".$hostID." ".$mapName." " . $agentName);

        echo '{"status":"success"}';

        //header('location: '.Config::$TOP_PATH.'competition');
	}
}
