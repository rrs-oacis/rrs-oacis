<?php
namespace rrsoacis\component\dashboard;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;

class RestrictedDashboardController extends AbstractController{
	
	public function get(){
		
		include (Config::$SRC_REAL_URL . 'component/dashboard/RestrictedDashboardView.php');
	}
	
}
