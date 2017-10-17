<?php
namespace rrsoacis\component\setting\general;

use rrsoacis\manager\AccessManager;
use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;

class SettingsLoginAuthController extends AbstractController
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
        else
        {
            header('location: '.Config::$TOP_PATH.'settings-login');
        }
	}

}
