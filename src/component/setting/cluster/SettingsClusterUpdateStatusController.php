<?php
namespace rrsoacis\component\setting\cluster;

use rrsoacis\manager\ClusterManager;
use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;

class SettingsClusterUpdateStatusController extends AbstractController
{
    public function get()
    {
        ClusterManager::updateAllStatus();

        header('location: '.Config::$TOP_PATH.'settings-clusters');
    }
}
?>
