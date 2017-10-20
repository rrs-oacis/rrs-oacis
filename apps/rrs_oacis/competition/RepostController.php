<?php
namespace rrsoacis\apps\rrs_oacis\competition;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\apps\rrs_oacis\results\model\MapResultGeneration;
use rrsoacis\apps\rrs_oacis\results\model\ResultHelper;

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
