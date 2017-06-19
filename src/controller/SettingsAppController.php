<?php
namespace adf\controller;

use adf\Config;
use adf\controller\AbstractController;
use adf\file\AppLoader;

class SettingsAppController extends AbstractController
{
    public function anyIndex($param = null)
    {
        self::get($param);
    }

    public function get ($param = null)
    {
        $app = AppLoader::getApp($param);
        include(Config::$SRC_REAL_URL . 'view/SettingsAppView.php');
    }
}
?>
