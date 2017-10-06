<?php
namespace rrsoacis\component\setting\cluster;

use rrsoacis\manager\ClusterManager;
use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;

class SettingsClusterRemoveController extends AbstractController
{
    public function anyIndex($param = null)
    {
        self::get($param);
    }

    public function get ($clusterName = null)
    {
        ClusterManager::removeCluster($clusterName);
        header('location: '.Config::$TOP_PATH.'settings-clusters');
    }
}
?>
