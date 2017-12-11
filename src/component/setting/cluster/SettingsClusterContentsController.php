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

		$isDisabled = false;
		if ($cluster["check_status"] == 3)
		{
			$isDisabled = true;
			$checkMessage = "Disabled";
			$checkMessageArray = [];
		}
		else
		{
			$checkMessage = ClusterManager::getClusterRawCheckMessage($clusterName);
			$checkMessageArray = preg_grep("/^@.\d/", explode("\n", $checkMessage));
		}

		$hasError = [];
		$message = [];
		foreach (["S", "A", "F", "P"] as $alias)
		{
			$hasError[$alias] = true;
			$message[$alias] = "";
		}

		foreach ($checkMessageArray as $msg)
		{
			$errorCode = preg_replace('/^@(.\d)/', '${1}', $msg);
			$node = substr($errorCode, 0, 1);
			$code = intval(substr($errorCode, 1));
			if ($message[$node] !== "") { continue; }
			if ($code == 0) {
				$message[$node] = "Ready (" . preg_replace('/^@.\d (.+)$/', '${1}', $msg) . ")";
				$hasError[$node] = false;
			} else if ($code == 20) {
				$message[$node] = "Cannot connect to host";
			} else if ($code == 24) {
				$message[$node] = "Is not permitted to use GUI";
			} else {
				$message[$node] = "Error:".$code;
			}
		}

		include(Config::$SRC_REAL_URL . 'component/setting/cluster/SettingsClusterContentsView.php');
	}
}
?>
