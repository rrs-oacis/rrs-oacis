<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/23
 * Time: 16:37
 */

namespace rrsoacis\component\map;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\MapManager;

class MapController extends AbstractController{

    public function anyIndex($param= null){
        self::get($param);
    }

    public function get($param = null){

        $map = MapManager::getMap($param);

        include (Config::$SRC_REAL_URL . 'component/map/MapView.php');
    }

}
?>