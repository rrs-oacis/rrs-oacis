<?php
namespace adf\controller;

use adf\controller\AbstractController;
use adf\file\AgentLoader;

class AgentListGetController extends AbstractController
{
	public function get()
    {
		echo json_encode( AgentLoader::getAgents() );
	}
	
}