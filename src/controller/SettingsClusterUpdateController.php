<?php

namespace adf\controller;

use adf\file\ClusterLoader;
use ZipArchive;

use adf\file\MapLoader;
use adf\Config;

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

        if ($a_host !== ''
            && $f_host !== ''
            && $p_host !== '')
        {
            ClusterLoader::updateCluster($name, $a_host, $f_host, $p_host, $s_host);
        }

        header('location: '.Config::$TOP_PATH.'settings-clusters');
	}
}

?>