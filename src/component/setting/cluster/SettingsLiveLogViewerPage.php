<?php
namespace rrsoacis\component\setting\cluster;

use rrsoacis\component\common\AbstractPage;
use rrsoacis\exception\HttpMethodNotAllowedException;
use rrsoacis\manager\ClusterManager;
use rrsoacis\manager\component\Cluster;
use rrsoacis\system\Config;

class SettingsLiveLogViewerPage extends AbstractPage
{
    private $cluster;
    private $fileName;
    public function controller($params)
    {
        if (count($params) < 1 ) {
            foreach (ClusterManager::getClusters() as $cluster) {
                if ($cluster["check_status"] != 0) { continue; }
                ?>
                <button class="btn btn-default" onclick="window.open('/settings-cluster_livelog/<?= $cluster["name"] ?>?list','ll','menubar=no, toolbar=no, scrollbars=no')"><?= $cluster["name"] ?></button><br>
                <?php
            }
            exit();
        }
        $cluster = ClusterManager::getCluster($params[0]);
        if ($cluster == null) {
            throw new HttpMethodNotAllowedException();
        }

        $this->fileName = "null";
        if (isset($params[1])) {
            $this->fileName = $params[1];
        }

        $this->cluster = new Cluster($cluster);
        $this->setTitle("LiveLog: ".$this->cluster->name);

        if (isset($_GET["list"])) {
            $this->fileName = "FILE LIST";
            $this->header();
            print('<meta http-equiv="refresh" content="5; URL=/settings-cluster_livelog/' . $this->cluster->name . '?list">');
            $runId = $this->getRunId();
            if ($runId != null) {
                $runFiles = scandir("/home/oacis/rrs-oacis/rrsenv/workspace/" . $this->cluster->name . "/" . $runId);
                foreach ($runFiles as $runFile) {
                    if (preg_match("/\.(txt|log)$/", $runFile)) {
                        print("<button class='btn btn-default btn-flat' style='width:100%' onclick=\"location.href='/settings-cluster_livelog/" . $this->cluster->name . "/" . $runFile . "'\">" . $runFile . "</button><br>");
                    }
                }
            }
            print("</body></html>");
        } else if (isset($_GET["load"])) {
            $runId = $this->getRunId();
            $start = 1;
            if (isset($_GET["start"])) { $start += 0 + $_GET["start"]; }
            if ($runId != null) {
                exec("tail -n +".$start." /home/oacis/rrs-oacis/rrsenv/workspace/" . $this->cluster->name . "/" . $runId ."/".$this->fileName." | head -128", $out);
                foreach ($out as $line) {
                    print $line . "\n";
                }
            }
        } else {
            $this->header();
            $this->body();
            $this->footer();
        }

	}

    function getRunId()
    {
        $result = null;
        $wsFiles = scandir("/home/oacis/rrs-oacis/rrsenv/workspace/".$this->cluster->name);
        foreach ($wsFiles as $wsFile) {
            if (strpos($wsFile, "_time.txt") == 24){
                $result = str_replace("_time.txt", "", $wsFile);
                break;
            }
        }
        return $result;
    }

	function body()
    {
        ?>
        <div class="term" id="term">
					<div id="fixedterm"></div>
					<div id="curterm"></div>
				</div>
        <?php
    }

    function header()
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <?php $title = $this->_pageTitle; ?>
            <?php include Config::$SRC_REAL_URL . 'component/common/head.php';?>
            <style type="text/css">
                body {
                    margin: 0;
                    padding: 0;
                }
                div.head {
                    background-color: #337ab7;
                    color: white;
                    padding: 10px;
                }
                div.term {
                    margin: 0;
                    padding: 0;
                    background-color: #000;
                    font-size: 1.2em;
                    color: white;
                    height: 90%;
                    width: 100%;
                    overflow-x: auto;
                    overflow-y: scroll;
                    word-break : break-all;
                    word-wrap: break-word;
                    white-space: normal;
                }
            </style>
        </head>
        <body>
        <div class="head" id="head">
            <i class="fa fa-terminal"></i>
            [LiveLog] <?= $this->cluster->name ?> : <?= $this->fileName ?>
            <div class="pull-right" style="margin-top: -2px;">
                <button class="btn btn-xs btn-default" onclick="location.href='/settings-cluster_livelog/<?= $this->cluster->name ?>?list'"><i class="fa fa-bars"></i></button>
            </div>
            <div class="pull-right" style="margin-top: -2px; margin-right: 5px;">
                <button class="btn btn-xs btn-warning" id="asbutton" onclick="as_change()">AutoScroll</button>
            </div>
        </div>
        <?php
    }

    function footer()
    {
        ?>
        </body>
        <script type="text/javascript">
            var autoscroll_flag = true;
            var line = 0;
            var prevLineContent = "";
            var load = function () {
                simpleget('/settings-cluster_livelog/<?= $this->cluster->name ?>/<?= $this->fileName ?>?load&start='+line,
                function (recv) {
                    arr = recv.split(/\r\n|\r|\n/);
                    line += arr.length-2;
                    for (i = 0; i < arr.length -2; i++) {
											  document.getElementById('fixedterm').innerHTML += arr[i] + "<br>";
                    }
                    if (2 <= arr.length && prevLineContent != arr[arr.length -2]) {
                        document.getElementById('curterm').innerHTML = arr[arr.length -2];
                        prevLineContent = arr[arr.length -2];
                    }
                    if (autoscroll_flag) { scroll(); }
                    setTimeout(load, (arr.length <= 2 ? 3000 : 500));
                });
            }
            load();
            var resize = function () {
                $('#term').height($(window).height()-$('#head').height());
            }
            resize();
            var scroll = function () {
                $('#term').animate({scrollTop: $('#term')[0].scrollHeight}, 'fast');
            }
            scroll();
            $(window).on('resize', function(){resize();});
            var as_change = function () {
                autoscroll_flag = !autoscroll_flag;
                $("#asbutton").toggleClass("btn-default", !autoscroll_flag);
                $("#asbutton").toggleClass("btn-warning", autoscroll_flag);
            }
        </script>
        </html>
        <?php
    }
}
