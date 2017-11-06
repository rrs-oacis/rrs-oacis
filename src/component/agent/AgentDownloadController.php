<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/11/06
 * Time: 11:13
 */

namespace rrsoacis\component\agent;

use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;

class AgentDownloadController extends AbstractController{


    public function anyIndex($param= null){

        AgentManager::all_zip($param);
        
    }

}
