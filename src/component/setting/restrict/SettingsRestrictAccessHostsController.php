<?php
namespace rrsoacis\component\setting\restrict;

use rrsoacis\system\Config;
use rrsoacis\manager\AccessManager;
use rrsoacis\component\common\AbstractController;

class SettingsRestrictAccessHostsController extends AbstractController
{
    public function post()
    {
        $hosts = $_POST['hosts'];

        AccessManager::setUnrestrictedHost($hosts);

        header('location: '.Config::$TOP_PATH.'settings-restrict');
    }
}
?>
