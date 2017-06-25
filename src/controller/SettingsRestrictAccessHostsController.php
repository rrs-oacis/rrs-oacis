<?php
namespace adf\controller;

use adf\Config;
use adf\manager\AccessManager;

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
