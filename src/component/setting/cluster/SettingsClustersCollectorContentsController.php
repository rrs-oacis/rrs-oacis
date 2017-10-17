<?php
namespace rrsoacis\component\setting\cluster;

use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\ClusterManager;

class SettingsClustersCollectorContentsController extends AbstractController
{
    public function anyIndex($param = null)
    {
        self::get($param);
    }

    public function get ($cmd = null)
    {
        $ronc = "/tmp/ronc.txt";
        if ($cmd === "start")
        {
            exec("nohup java -jar /home/oacis/rrs-oacis/public/RrsoacisNodeCollector/RrsoacisNodeCollector.jar r 6 >> ".$ronc." &", $exec_out, $internet);
        }
        else if ($cmd === "next")
        {
            exec("java -jar /home/oacis/rrs-oacis/public/RrsoacisNodeCollector/RrsoacisNodeCollector.jar e", $exec_out, $internet);
        }
        else if ($cmd === "reset")
        {
            exec("rm -f ".$ronc, $exec_out, $internet);
        }

        if (file_exists($ronc))
        {
            include $ronc;
        }
    }
}
?>
