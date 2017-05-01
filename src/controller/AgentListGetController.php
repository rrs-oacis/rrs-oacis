<?php
namespace adf\controller;

use adf\controller\AbstractController;
use adf\file\AgentLoader;

class AgentListGetController extends AbstractController{
	
	public function get(){
		
		//エージェントのリストを取得
		$agents = AgentLoader::getAgents();
		
		echo json_encode($agents);
		
		//include (Config::$SRC_REAL_URL . 'view/AgentListView.php');
	}
	
}