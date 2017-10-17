<?php
namespace rrsoacis\component\setting\general;

use rrsoacis\manager\AccessManager;
use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;

class SettingsLoginController extends AbstractController
{

	public function post()
    {
        if (isset($_POST["password"]))
        {
            $sessionId = AccessManager::getSessionId($_POST["password"]);
            if ($sessionId !== "")
            {
                setcookie("roid", $sessionId);
                header('location: '.Config::$TOP_PATH);
            }
        }
		include (Config::$SRC_REAL_URL . 'component/setting/general/SettingsLoginView.php');
	}

}
