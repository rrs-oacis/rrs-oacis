<?php
namespace rrsoacis\component\setting\general;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;

class SettingsVersionUpdateController extends AbstractController
{
	
	public function get()
    {
        exec("timeout 3 git fetch", $exec_out, $exec_ret);
        $exec_out = (count($exec_out) >= 1? $exec_out[0] : "");
        if ($exec_ret != 0
            && (strpos($exec_out,'verification') !== false
                || strpos($exec_out,'Permission') !== false))
        {
            exec("git remote set-url origin https://github.com/rrs-oacis/rrs-oacis.git");
            exec("git remote set-url --push origin git@github.com:rrs-oacis/rrs-oacis.git");
            exec("timeout 3 git fetch", $exec_out, $exec_ret);
        }

        if ($exec_ret == 0)
        {
            exec("git pull");
        }

        header('location: '.Config::$TOP_PATH.'settings-general');
	}
	
}
