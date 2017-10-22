<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/22
 * Time: 21:15
 */

namespace rrsoacis\apps\rrs_oacis\live_console;


use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\ClusterManager;
use rrsoacis\system\Config;

class ClusterGetController extends AbstractController
{

    const CLUSTER_DIR = "rrsenv/workspace";

    const STD_OUT_FILE = "_stdout.txt";
    const STD_ERROR_FILE = "_stderr.txt";

    public function anyIndex($clusterName = null,$stdType = null){

        $logFiles = scandir(Config::$ROUTER_PATH.self::CLUSTER_DIR. "/".$clusterName);

        foreach($logFiles as $logDirectory){

            if ($logDirectory === '.' || $logDirectory === '..') { continue; }
            if (! is_dir(Config::$ROUTER_PATH.self::CLUSTER_DIR."/".$clusterName."/".$logDirectory)) { continue; }
            if (preg_match("/_log$/",$logDirectory)){ continue; }

            $logFile = '';

            if($stdType ==='out'){
                $logFile = Config::$ROUTER_PATH.self::CLUSTER_DIR."/".$clusterName."/".$logDirectory. "/" . self::STD_OUT_FILE;
            }else if($stdType == 'error'){
                $logFile = Config::$ROUTER_PATH.self::CLUSTER_DIR."/".$clusterName."/".$logDirectory. "/" . self::STD_ERROR_FILE;
            }

            if (file_exists($logFile)) {
                $text = file_get_contents($logFile);

                echo $text;

            }



        }



        //echo json_encode(ClusterManager::getCluster($clusterName));


        //echo json_encode( AgentManager::getAgents() );
    }

}