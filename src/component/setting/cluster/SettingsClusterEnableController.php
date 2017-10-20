<?php
namespace rrsoacis\component\setting\cluster;

use rrsoacis\manager\ClusterManager;
use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;

class SettingsClusterEnableController extends AbstractController
{
    public function anyIndex($clusterName = null, $isEnable = 1)
    {
        self::get($clusterName, $isEnable);
    }

    public function get ($clusterName = null, $isEnable = 1)
    {
        if ($isEnable == 1)
        { ClusterManager::setEnable($clusterName); }
        else
        { ClusterManager::setDisable($clusterName); }

        header('location: '.Config::$TOP_PATH.'settings-cluster/'.$clusterName);
    }
}
?>
