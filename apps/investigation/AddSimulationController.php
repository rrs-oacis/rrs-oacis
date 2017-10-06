<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/06
 * Time: 15:48
 */

namespace rrsoacis\apps\investigation;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;

class AddSimulationController extends AbstractController
{
    public function post()
    {

        $agents = $_POST['parameter_agents'];
        $maps = $_POST['parameter_maps'];

        $tags = $_POST['parameter_tags'];

        $count = $_POST['parameter_count'];


        //SessionManager::addSession($alias, $agents, $precursor, $highlight);

        //$output = shell_exec("sh ". Config::$ROUTER_PATH. "ruby/add_agent.sh ".$simulatorID." ".$hostID." ".$mapName." " . $agentName);

        echo '{"status":"success"}';

        //header('location: '.Config::$TOP_PATH.'competition');
    }
}