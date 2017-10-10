<?php

namespace rrsoacis\component\setting\cluster;

use rrsoacis\manager\ClusterManager;
use ZipArchive;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\MapManager;
use rrsoacis\system\Config;

class SettingsClusterUpdateController extends AbstractController
{
	public function post()
    {
        $name = $_POST['name'];
        if ($name === '')
        {
            $name = null;
        }

        $a_host = $_POST['a_host'];
        $f_host = $_POST['f_host'];
        $p_host = $_POST['p_host'];
        $s_host = $_POST['s_host'];
        $hosts_pass = $_POST['hosts_pass'];

        if ($a_host !== ''
            && $s_host !== ''
            && $f_host !== ''
            && $p_host !== '')
        {
            ClusterManager::updateCluster($name, $a_host, $f_host, $p_host, $s_host, $hosts_pass);
        }

        if ($name == null)
        { header('location: '.Config::$TOP_PATH.'settings-clusters'); }
        else
        { header('location: '.Config::$TOP_PATH.'settings-cluster/'.$name); }
	}
}

?>