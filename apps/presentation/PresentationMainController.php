<?php
namespace adf\apps\presentation;

use adf\Config;
use adf\controller\AbstractController;
use adf\file\AppLoader;

class PresentationMainController extends AbstractController
{
    public function get()
    {
        $apps = AppLoader::getApps();
        include(dirname(__FILE__).'/PresentationMainView.php');
    }
}
?>
