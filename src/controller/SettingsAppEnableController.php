<?php
namespace adf\controller;

use adf\Config;
use adf\controller\AbstractController;
use adf\file\AppLoader;

class SettingsAppEnableController extends AbstractController
{
    public function anyIndex($param1 = null, $param2 = 1)
    {
        self::get($param1, $param2);
    }

    public function get ($packageName = null, $isEnable = 1)
    {
        if ($isEnable == 1) { AppLoader::setEnable($packageName); }
        else { AppLoader::setDisable($packageName); }

        header('location: '.Config::$TOP_PATH.'settings-app/'.$packageName);
    }
}
?>
