<?php
namespace adf\controller;

use adf\Config;
use adf\manager\AccessManager;

class SettingsRestrictAccessSetController extends AbstractController
{
    public function anyIndex($param = 0)
    {
        self::get($param);
    }

    public function get ($isEnable = 0)
    {
        if ($isEnable == 1) { AccessManager::enableFilter(); }
        else { AccessManager::disableFilter(); }

        header('location: '.Config::$TOP_PATH.'settings-restrict');
    }
}
?>
