<?php
namespace rrsoacis\component\setting\app;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AppManager;

class SettingsAppEnableController extends AbstractController
{
    public function anyIndex($param1 = null, $param2 = 1)
    {
        self::get($param1, $param2);
    }

    public function get ($packageName = null, $isEnable = 1)
    {
        if ($isEnable == 1) { AppManager::setEnable($packageName); }
        else { AppManager::setDisable($packageName); }

        header('location: '.Config::$TOP_PATH.'settings-app/'.$packageName);
    }
}
?>
