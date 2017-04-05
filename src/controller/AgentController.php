<?php
namespace adf\controller;

use adf\Config;
use adf\controller\AbstractController;
use adf\file\AgentLoader;

class AgentController extends AbstractController{
	
	public function anyIndex($param= null){
		self::get($param);
	}
	
	public function get($param = null){
		//エージェントのリストを取得
		$agent = AgentLoader::getAgent($param);
		
		//echo $agent["name"];
		
		include (Config::$SRC_REAL_URL . 'view/AgentView.php');
	}
	
}