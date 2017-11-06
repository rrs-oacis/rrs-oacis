<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/11/06
 * Time: 11:33
 */

namespace rrsoacis\component\map;

use rrsoacis\manager\MapManager;
use rrsoacis\component\common\AbstractController;


class MapDownloadController extends AbstractController{

    public function anyIndex($param= null){

        MapManager::all_zip($param);

    }

}
