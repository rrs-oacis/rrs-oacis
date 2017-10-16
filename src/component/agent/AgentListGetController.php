<?php
namespace rrsoacis\component\agent;

use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;

class AgentListGetController extends AbstractController
{
	public function get()
    {
		echo json_encode( AgentManager::getAgents() );
	}

}