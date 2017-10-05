<?php

namespace rrsoacis\apps\tc2017;

use rrsoacis\apps\competition\SessionManager;
use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;

class AddSessionController extends AbstractController
{
    public function post()
    {
        $alias = $_POST['session_name'];
        $tdText = $_POST['td_agents'];
        $baseText = $_POST['base_agents'];

        $tdAgents = explode(',', $tdText);
        $baseAgents = explode(',', $baseText);

        $agents = [];
        foreach ($tdAgents as $tdAgent)
        {
            foreach ($baseAgents as $baseAgent)
            {
                $agents[] = TCCoordinator::blendTeams(
                    AgentManager::getAgentByAlias($baseAgent)['name'],
                    AgentManager::getAgentByAlias($tdAgent)['name']
                )['alias'];
            }
        }

        SessionManager::addSession($alias, $agents);

        header('location: '.Config::$TOP_PATH.'competition');
    }
}