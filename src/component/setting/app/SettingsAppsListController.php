<?php
namespace rrsoacis\component\setting\app;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AppManager;

class SettingsAppsListController extends AbstractController
{
    public function get()
    {
        $apps = AppManager::getApps();
        include (Config::$SRC_REAL_URL . 'component/setting/app/SettingsAppsListView.php');
    }
}
?>
