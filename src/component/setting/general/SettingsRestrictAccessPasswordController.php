<?php
namespace rrsoacis\component\setting\general;

use rrsoacis\system\Config;
use rrsoacis\manager\AccessManager;
use rrsoacis\component\common\AbstractController;

class SettingsRestrictAccessPasswordController extends AbstractController
{
    public function post()
    {
        $password = $_POST['password'];

        AccessManager::setPassword($password);

        header('location: '.Config::$TOP_PATH.'settings-general');
    }
}
?>
