<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/23
 * Time: 17:35
 */

namespace rrsoacis\component\map;

use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\MapManager;


class MapArchivedListGetController extends AbstractController
{
    public function get()
    {
        echo json_encode( MapManager::getArchivedMaps() );
    }

}