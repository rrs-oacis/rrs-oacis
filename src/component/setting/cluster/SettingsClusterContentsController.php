<?php
namespace rrsoacis\component\setting\cluster;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\ClusterManager;

class SettingsClusterContentsController extends AbstractController
{
    public function anyIndex($param = null)
    {
        self::get($param);
    }

    public function get ($clusterName = null)
    {
        $cluster = ClusterManager::getCluster($clusterName);

        if ($cluster["check_status"] == 3)
        {
            $checkMessage = "Disabled";
            $checkMessageArray = [];
        }
        else
        {
            $checkMessage = ClusterManager::getClusterRawCheckMessage($clusterName);
            $checkMessageArray = preg_grep("/^@.\d/", explode("\n", $checkMessage));
        }

        $hasError = [];
        $javaVer = [];
        foreach (["S", "A", "F", "P"] as $alias)
        {
            $hasError[$alias] = false;
            $javaVer[$alias] = "";
        }

        foreach ($checkMessageArray as $msg)
        {
            $errorCode = preg_replace('/^@(.\d)/', '${1}', $msg);
            $node = substr($errorCode, 0, 1);
            $code = intval(substr($errorCode, 1));
            if ($code == 0)
            { $javaVer[$node] = preg_replace('/^@.\d (.+)$/', '${1}', $msg); }
            else
            { $hasError[$node] = true; }
        }

        foreach (["S", "A", "F", "P"] as $alias)
        {
            if ($javaVer[$alias] == "")
            { $hasError[$alias] = true; }
        }

        include(Config::$SRC_REAL_URL . 'component/setting/cluster/SettingsClusterContentsView.php');
    }
}
?>
