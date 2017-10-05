<?php
namespace rrsoacis\component\setting\app;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AppManager;

class SettingsAppController extends AbstractController
{
    public function anyIndex($param = null)
    {
        self::get($param);
    }

    public function get ($param = null)
    {
        $app = AppManager::getApp($param);
        include(Config::$SRC_REAL_URL . 'component/setting/app/SettingsAppView.php');
    }
}
?>
