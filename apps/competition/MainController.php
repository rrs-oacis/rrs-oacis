<?php
namespace rrsoacis\apps\competition;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AppManager;
use rrsoacis\manager\MapManager;
use rrsoacis\manager\AgentManager;

class MainController extends AbstractController
{
    public function get()
    {
        $sessions = SessionManager::getSessions();
        $maps = MapManager::getMaps();
        $agents = AgentManager::getAgents();

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
