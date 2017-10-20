<?php
namespace rrsoacis\component\setting\cluster;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AppManager;
use rrsoacis\manager\ClusterManager;

class SettingsClustersListWidgetController extends AbstractController
{
    public function get()
    {
        $clusters = ClusterManager::getClusters();
        $needsRefresh = 0;
        $usedNodes = "";
        foreach ($clusters as $cluster) {
            if ($cluster["check_status"] == 1) {
                $needsRefresh = 1;
            }
            $usedNodes .= $cluster["s_host"] . "\n";
            $usedNodes .= $cluster["a_host"] . "\n";
            $usedNodes .= $cluster["f_host"] . "\n";
            $usedNodes .= $cluster["p_host"] . "\n";
        }
        ClusterManager::updateHostGroup();
        include(Config::$SRC_REAL_URL . 'component/setting/cluster/SettingsClustersListWidgetView.php');
    }
}

?>
