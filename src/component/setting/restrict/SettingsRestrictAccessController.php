<?php
namespace rrsoacis\component\setting\restrict;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;

class SettingsRestrictAccessController extends AbstractController
{
	
	public function get()
    {
		include (Config::$SRC_REAL_URL . 'component/setting/restrict/SettingsRestrictAccessView.php');
	}
	
}
