<?php
namespace rrsoacis\component\setting\general;

use rrsoacis\manager\AccessManager;
use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;

class SettingsLoginController extends AbstractController
{
	public function get()
    {
		include (Config::$SRC_REAL_URL . 'component/setting/general/SettingsLoginView.php');
	}

}
