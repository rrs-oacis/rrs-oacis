<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/11/06
 * Time: 15:40
 */

namespace rrsoacis\component\agent;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;

class AgentConfigGetController extends AbstractController
{

    public function anyIndex($param= null){
        self::get($param);
    }

    public function get($param = null){

        echo file_get_contents(Config::$SRC_REAL_URL.'../'.Config::AGENTS_DIR_NAME.'/'.$param.'/config/module.cfg');

    }
}