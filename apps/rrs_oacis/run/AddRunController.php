<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/06
 * Time: 15:48
 */

namespace rrsoacis\apps\rrs_oacis\run;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\apps\rrs_oacis\run\RunManager;

class AddRunController extends AbstractController
{
    public function post()
    {

        $agents = $_POST['parameter_agents'];
        $maps = $_POST['parameter_maps'];

        $tags_row = $_POST['parameter_tags'];

        $tags = '';

        for($i=0;$i<count($tags_row);$i++){
            $tags =  $tags . $tags_row[$i];
            if($i<count($tags_row)-1){
                $tags = $tags . ' ';
            }
        }

        $count = $_POST['parameter_count'];


        RunManager::addRuns($agents,$maps,$tags,$count);


        //SessionManager::addSession($alias, $agents, $precursor, $highlight);

        //$output = shell_exec("sh ". Config::$ROUTER_PATH. "ruby/add_agent.sh ".$simulatorID." ".$hostID." ".$mapName." " . $agentName);

        echo '{"status":"success"}';

        //header('location: '.Config::$TOP_PATH.'competition');
    }
}