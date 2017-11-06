<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/11/02
 * Time: 14:59
 */

namespace rrsoacis\component\agent;


use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;

class AgentsArchivedListController extends AbstractController
{

    public function get(){
        include (Config::$SRC_REAL_URL . 'component/agent/AgentsArchivedListView.php');
    }

}