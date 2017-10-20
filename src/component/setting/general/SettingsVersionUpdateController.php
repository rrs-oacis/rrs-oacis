<?php
namespace rrsoacis\component\setting\general;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;

class SettingsVersionUpdateController extends AbstractController
{
	
	public function get()
    {
        exec("timeout 5 ping 8.8.8.8 -c 1", $exec_out, $internet);
        $internet = ($internet == 0);
        if ($internet)
        {
            exec("timeout 30 git fetch", $exec_out, $exec_ret);
            $exec_out = (count($exec_out) >= 1? $exec_out[0] : "");
            if ($exec_ret != 0
                && (strpos($exec_out,'verification') !== false
                    || strpos($exec_out,'Permission') !== false))
            {
                exec("cd /home/oacis/rrs_oacis; git remote set-url origin https://github.com/rrs_oacis/rrs_oacis.git");
                exec("cd /home/oacis/rrs_oacis; git remote set-url --push origin git@github.com:rrs_oacis/rrs_oacis.git");
                exec("timeout 30 git fetch", $exec_out, $exec_ret);

                exec("cd /home/oacis/rrs-oacis/rrsenv; git remote set-url origin https://github.com/tkmnet/rrsenv.git");
                exec("cd /home/oacis/rrs-oacis/rrsenv; git remote set-url --push origin git@github.com:tkmnet/rrsenv.git");
            }

            if ($exec_ret == 0)
            {
                exec("cd /home/oacis/rrs-oacis; git pull");
                exec("cd /home/oacis/rrs-oacis/rrsenv; git pull");
            }
        }

        header('location: '.Config::$TOP_PATH.'settings-general');
	}
	
}
