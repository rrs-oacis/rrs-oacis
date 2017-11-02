<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/11/02
 * Time: 14:59
 */

namespace rrsoacis\component\map;


use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;

class MapsArchivedListController extends AbstractController
{

    public function get(){
        include (Config::$SRC_REAL_URL . 'component/map/MapsArchivedListView.php');
    }

}