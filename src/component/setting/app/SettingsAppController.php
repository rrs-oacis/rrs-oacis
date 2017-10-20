<?php
namespace rrsoacis\component\setting\app;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AppManager;

class SettingsAppController extends AbstractController
{
    public function anyIndex($param = null,$param2 = null)
    {
        self::get($param, $param2);
    }

    public function get ($param = null,$param2 = null)
    {
        $app = AppManager::getApp($param . "/" . $param2);
        include(Config::$SRC_REAL_URL . 'component/setting/app/SettingsAppView.php');
    }
}
?>
