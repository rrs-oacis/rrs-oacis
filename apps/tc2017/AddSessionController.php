<?php

namespace adf\apps\tc2017;

use adf\apps\competition\SessionManager;
use adf\Config;
use adf\controller\AbstractController;
use adf\file\AgentLoader;

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
                    AgentLoader::getAgentByAlias($baseAgent)['name'],
                    AgentLoader::getAgentByAlias($tdAgent)['name']
                )['alias'];
            }
        }

        SessionManager::addSession($alias, $agents);

        header('location: '.Config::$TOP_PATH.'competition');
    }
}