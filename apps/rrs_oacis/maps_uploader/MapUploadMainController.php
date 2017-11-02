<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/06
 * Time: 14:04
 */
namespace rrsoacis\apps\rrs_oacis\maps_uploader;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AppManager;
use rrsoacis\manager\MapManager;
use rrsoacis\manager\AgentManager;

class MapUploadMainController extends AbstractController
{
    public function get()
    {

        include(dirname(__FILE__) . '/MapUploadMainView.php');
    }
}
?>
