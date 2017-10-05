<?php
namespace rrsoacis\component\dashboard;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;

class DashboardController extends AbstractController{
	
	public function get(){
		
		//エージェントのリストを取得
		//$agents = AgentLoader::getAgents();
		
		include (Config::$SRC_REAL_URL . 'component/dashboard/DashboardView.php');
	}
	
}
