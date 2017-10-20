<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/06
 * Time: 16:12
 */
namespace rrsoacis\apps\rrs_oacis\run;

use rrsoacis\component\common\AbstractController;
use rrsoacis\apps\rrs_oacis\run\RunManager;

class RunListGetController extends AbstractController
{
    public function get()
    {
        echo json_encode( RunManager::getRuns() );
    }

}