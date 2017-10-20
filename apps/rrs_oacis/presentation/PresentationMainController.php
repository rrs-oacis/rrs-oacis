<?php
namespace rrsoacis\apps\rrs_oacis\presentation;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AppManager;

class PresentationMainController extends AbstractController
{
    public function get()
    {
        $apps = AppManager::getApps();
        include(dirname(__FILE__) . '/PresentationMainView.php');
    }
}
?>
