<?php
namespace adf\controller;

use adf\Config;
use adf\controller\AbstractController;
use adf\file\AppLoader;

class SettingsAppsListController extends AbstractController
{
    public function get()
    {
        $apps = AppLoader::getApps();
        include (Config::$SRC_REAL_URL . 'view/SettingsAppsListView.php');
    }
}
?>
