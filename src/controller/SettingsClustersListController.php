<?php
namespace adf\controller;

use adf\Config;
use adf\controller\AbstractController;
use adf\file\AppLoader;
use adf\file\ClusterLoader;

class SettingsClustersListController extends AbstractController
{
    public function get()
    {
        $clusters = ClusterLoader::getClusters();
        include(Config::$SRC_REAL_URL . 'view/SettingsClustersListView.php');
    }
}
?>
