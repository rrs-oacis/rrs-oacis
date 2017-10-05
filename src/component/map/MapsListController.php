<?php
namespace rrsoacis\component\map;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;


class MapsListController extends AbstractController{
	
	public function get(){
		include (Config::$SRC_REAL_URL . 'component/map/MapsListView.php');
	}
	
}