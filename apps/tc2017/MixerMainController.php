<?php
namespace adf\apps\tc2017;

use adf\controller\AbstractController;
use adf\file\AppLoader;

class MixerMainController extends AbstractController
{
    public function get()
    {
        $apps = AppLoader::getApps();
        include(dirname(__FILE__).'/MixerMainView.php');
    }
}
?>
