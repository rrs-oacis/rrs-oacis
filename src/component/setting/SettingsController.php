<?php
namespace rrsoacis\component\setting;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;

class SettingsController extends AbstractController{
	
	public function get(){
		
		//エージェントのリストを取得
		$agents = AgentManager::getAgents();
		
		include (Config::$SRC_REAL_URL . 'component/setting/SettingsView.php');
	}
	
}
