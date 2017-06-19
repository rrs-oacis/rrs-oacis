<?php
namespace adf\apps\manual_post2;

use adf\Config;
use adf\controller\AbstractController;
use adf\file\AppLoader;

class ManualPostMainController extends AbstractController
{
    public function get()
    {
        $apps = AppLoader::getApps();
        include(dirname(__FILE__).'/ManualPostMainView.php');
    }
}
?>
