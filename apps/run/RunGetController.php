<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/07
 * Time: 14:30
 */

namespace rrsoacis\apps\run;

use rrsoacis\component\common\AbstractController;
use rrsoacis\apps\run\RunManager;


class RunGetController extends AbstractController
{
    public function anyIndex($param= null){
        return self::get($param);
    }

    public function get($name = null){

        echo json_encode( RunManager::getRun($name) );

    }

}