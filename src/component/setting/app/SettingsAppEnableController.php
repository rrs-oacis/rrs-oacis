<?php
namespace rrsoacis\component\setting\app;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AppManager;

class SettingsAppEnableController extends AbstractController
{
    public function anyIndex($param1 = null,$param2 = null, $param3 = 1)
    {
        self::get($param1, $param2, $param3);
    }

    public function get ($userName = null, $packageName = null, $isEnable = 1)
    {
        if ($isEnable == 1) { AppManager::setEnable($userName . "/" . $packageName); }
        else { AppManager::setDisable($userName . "/" . $packageName); }

        header('location: '.Config::$TOP_PATH.'settings-app/'. $userName . "/" . $packageName);
    }
}
?>
