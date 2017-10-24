<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/24
 * Time: 10:28
 */

namespace rrsoacis\apps\rrs_oacis\archived;

use rrsoacis\component\common\AbstractController;


class ArchivedMainController extends AbstractController
{
    public function get()
    {

        //$maps = MapManager::getMaps();
        //$agents = AgentManager::getAgents();


        //$apps = AppManager::getApps();
        include(dirname(__FILE__) . '/ArchivedMainView.php');
    }
}
?>
