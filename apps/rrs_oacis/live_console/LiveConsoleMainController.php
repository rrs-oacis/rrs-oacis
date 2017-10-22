<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/21
 * Time: 22:02
 */

namespace rrsoacis\apps\rrs_oacis\live_console;

use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\ClusterManager;

class LiveConsoleMainController extends AbstractController
{
    public function get()
    {

        $clusters = ClusterManager::getClusters();

        $cluster = "59d849a8b154111ce367d2f4";



        include(dirname(__FILE__) . '/LiveConsoleMainView.php');

    }

}
?>
