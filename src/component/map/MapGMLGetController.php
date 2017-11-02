<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/08
 * Time: 16:45
 */



namespace rrsoacis\component\map;

use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\MapManager;
use rrsoacis\system\Config;

class MapGMLGetController extends AbstractController
{

    public function anyIndex($param= null){
        self::get($param);
    }

    public function get($param = null){

        echo file_get_contents(Config::$SRC_REAL_URL.'../rrsenv/MAP/'.$param.'/map/map.gml');

    }
}