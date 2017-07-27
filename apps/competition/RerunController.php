<?php
namespace adf\apps\competition;

use adf\Config;

use adf\controller\AbstractController;
use adf\apps\results\model\MapResultGeneration;
use adf\apps\results\model\ResultHelper;

class RerunController extends AbstractController{

	public function anyIndex($param= null){
		return self::get($param);
	}

	public function get($runId= null){
		/*error_reporting(0);*/

		SessionManager::rerun($runId);

		header('location: '.Config::$TOP_PATH.'competition');
	}
}
