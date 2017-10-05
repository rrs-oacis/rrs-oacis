<?php
namespace rrsoacis\apps\competition;

use rrsoacis\system\Config;

use rrsoacis\component\common\AbstractController;
use rrsoacis\apps\results\model\MapResultGeneration;
use rrsoacis\apps\results\model\ResultHelper;

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
