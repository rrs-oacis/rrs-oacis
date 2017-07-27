<?php
namespace adf\apps\competition;

use adf\Config;
use adf\controller\AbstractController;
use adf\apps\results\model\MapResultGeneration;
use adf\apps\results\model\ResultHelper;

class RepostController extends AbstractController{

	public function anyIndex($param = null, $param2 = null, $param3 = null){
		return self::get($param, $param2, $param3);
	}

	public function get($s = null, $m = null, $a = null){
		/*error_reporting(0);*/

		SessionManager::repost($s, $m , $a);

		header('location: '.Config::$TOP_PATH.'competition');
	}
}
