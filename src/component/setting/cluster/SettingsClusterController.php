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
        $checkMessage = ClusterManager::getClusterRawCheckMessage($clusterName);
        $checkMessageArray = preg_grep("/^@.\d/", explode("\n", $checkMessage));

        $hasError = [];
        $hasError["S"] = false;
        $hasError["A"] = false;
        $hasError["F"] = false;
        $hasError["P"] = false;

        foreach ($checkMessageArray as $msg)
        {
            $errorCode = preg_replace('/^@(.\d)/', '${1}', $msg);
            $node = substr($errorCode, 0, 1);
            $code = intval(substr($errorCode, 1));
            if ($code != 0)
            {
                $hasError[$node] = true;
            }
        }


        include(Config::$SRC_REAL_URL . 'component/setting/cluster/SettingsClusterView.php');
    }
}
?>
