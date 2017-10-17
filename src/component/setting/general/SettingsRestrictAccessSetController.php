<?php
namespace rrsoacis\component\setting\general;

use rrsoacis\system\Config;
use rrsoacis\manager\AccessManager;
use rrsoacis\component\common\AbstractController;

class SettingsRestrictAccessSetController extends AbstractController
{
    public function anyIndex($param = 0)
    {
        self::get($param);
    }

    public function get ($isEnable = 0)
    {
        if ($isEnable == 1) { AccessManager::enableFilter(); }
        else if ($isEnable == 0){ AccessManager::disableFilter(); }
        else if ($isEnable == 3){ AccessManager::enablePasswordProtect(); }
        else if ($isEnable == 2){ AccessManager::disablePasswordProtect(); }

        header('location: '.Config::$TOP_PATH.'settings-general');
    }
}
?>
