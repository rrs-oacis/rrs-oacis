<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/16
 * Time: 14:54
 */

namespace rrsoacis\component\agent;


use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;


class AgentArchivedController extends AbstractController
{
    public function post()
    {

        $agents = $_POST['parameter_name'];
        $agentsArchived = $_POST['parameter_archived'];

        AgentManager::setArchived($agents,$agentsArchived);


        return '{"result":"success"}';

    }

}