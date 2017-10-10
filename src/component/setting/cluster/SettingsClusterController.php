<?php
namespace rrsoacis\component\setting\cluster;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\ClusterManager;

class SettingsClusterController extends AbstractController
{
    public function anyIndex($param = null)
    {
        self::get($param);
    }

    public function get ($clusterName = null)
    {
        $cluster = ClusterManager::getCluster($clusterName);
        include(Config::$SRC_REAL_URL . 'component/setting/cluster/SettingsClusterView.php');
    }
}
?>
