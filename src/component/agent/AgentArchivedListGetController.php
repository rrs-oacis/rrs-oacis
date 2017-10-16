<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/16
 * Time: 14:50
 */

namespace rrsoacis\component\agent;

use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;


class AgentArchivedListGetController extends AbstractController
{
    public function get()
    {
        echo json_encode( AgentManager::getArchivedAgents() );
    }

}