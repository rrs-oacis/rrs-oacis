<?php
namespace adf\apps\competition;

use adf\Config;
use adf\controller\AbstractController;
use adf\file\AppLoader;
use adf\file\MapLoader;
use adf\file\AgentLoader;

class MainController extends AbstractController
{
    public function get()
    {
        $sessions = SessionManager::getSessions();
        $maps = MapLoader::getMaps();
        $agents = AgentLoader::getAgents();

	$agentAliasText = "";
	
	foreach ($agents as $agent)
	{
		$agentAliasText .= $agent['alias'].',';
	}
	$agentAliasText = substr($agentAliasText, 0, strlen($agentAliasText)-1);

        include(dirname(__FILE__).'/CompetitionMainView.php');
    }
}
?>
