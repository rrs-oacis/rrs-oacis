<?php
namespace rrsoacis\apps\rrs_oacis\tc2017;

use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AppManager;

class MixerMainController extends AbstractController
{
    public function get()
    {
        $apps = AppManager::getApps();
        include(dirname(__FILE__) . '/MixerMainView.php');
    }
}
?>
