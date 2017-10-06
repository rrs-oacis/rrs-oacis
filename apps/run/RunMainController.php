<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/06
 * Time: 14:04
 */
namespace rrsoacis\apps\run;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AppManager;
use rrsoacis\manager\MapManager;
use rrsoacis\manager\AgentManager;

class RunMainController extends AbstractController
{
    public function get()
    {

        $maps = MapManager::getMaps();
        $agents = AgentManager::getAgents();


        $apps = AppManager::getApps();
        include(dirname(__FILE__) . '/RunMainView.php');
    }
}
?>
