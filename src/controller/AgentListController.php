<?php
namespace adf\controller;

use adf\Config;
use adf\controller\AbstractController;
use adf\file\AgentLoader;

class AgentListController extends AbstractController{
	
	public function get(){
		include (Config::$SRC_REAL_URL . 'view/AgentListView.php');
	}
	
}