<?php
namespace rrsoacis\component\agent;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;

class AgentController extends AbstractController{
	
	public function anyIndex($param= null){
		self::get($param);
	}
	
	public function get($param = null){
		//エージェントのリストを取得
		$agent = AgentManager::getAgent($param);
		
		//echo $agent["name"];
		
		include (Config::$SRC_REAL_URL . 'component/agent/AgentView.php');
	}
	
}
?>