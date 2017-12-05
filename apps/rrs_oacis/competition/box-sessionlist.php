<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/10/06
 * Time: 10:37
 */

use rrsoacis\system\Config;

?>

<?php foreach ($sessions as $session) { ?>

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">
                <?= $session["alias"] ?>
                <small><?= $session["name"] ?></small>
            </h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tr>
                    <th>Agent＼Map</th>
                    <?php
                    if (count($session['maps']) <= 0) {
                        echo("<th>(no map)</th>");
                    } else {
                        foreach ($session['maps'] as $map) {
                            ?>
                            <th title="<?= $map['name'] ?> (<?= $map['timestamp'] ?>)"><?= $map['alias'] ?>
                                <form method="post" action="./competition-remove_map" style="display:inline;">
                                    <input type="hidden" name="parameter_session" value="<?= $session["name"] ?>">
                                    <input type="hidden" name="parameter_map" value="<?= $map['name'] ?>">
                                    <input class="btn-xs btn-warning" type="submit" value="X">
                                </form>
                            </th>
                            <?php
                        }
                    }
                    ?>
                </tr>
                <?php foreach ($session['agents'] as $agent) { ?>
                    <tr class="linked-rowstop" data-href="<?= Config::$TOP_PATH . "settings-clusters" ?>">
                        <?php if (isset($agent['name'])) { ?>
                            <th title="<?= $agent['name'] ?> (<?= $agent['timestamp'] ?>)"><?= $agent['alias'] ?></th>
                        <?php } else { ?>
                            <th style="color: silver;"><?= $agent['alias'] ?></th>
                        <?php } ?>
                        <?php
                        if (count($session['maps']) <= 0) {
                            echo("<td></td>");
                        } else {
                            foreach ($session['maps'] as $map) {
                                $runId = $session['runs'][$map['name']][substr($agent['name'], 0, strlen($agent['name']) - 14)]['runId'];

                                if (isset($agent['name'])) {
                                    if ($runId != '') {
                                        $runJson = '';
                                        $runJson = file_get_contents('http://localhost:3000/runs/' . $runId . '.json');
                                        $run = json_decode($runJson, true);
                                        $status = $run['status'];

                                        echo("<td>");

                                        if ($status === 'running' || $status === 'submitted') {
                                            echo("<a href=\"javascript:void(0);\" onclick=\"window.open('http://'+location.host.replace(location.port,3000)+'/runs/" . $runId . "');\">");
                                            echo("<span class='label label-primary'>running</span>");
                                            echo("</a>");
                                        } else if ($status === 'created') {
                                            echo("<a href=\"javascript:void(0);\" onclick=\"window.open('http://'+location.host.replace(location.port,3000)+'/runs/" . $runId . "');\">");
                                            echo("<span class='label label-warning'>reserved</span>");
                                            echo("</a>");
                                        } else if ($status === 'finished') {
                                            echo("<a href=\"javascript:void(0);\" onclick=\"window.open('http://'+location.host.replace(location.port,3000)+'/runs/" . $runId . "');\">");
                                            echo("<span class='label label-success'>finished</span>");
                                            echo("</a>");
                                            echo("<a href='./competition-rerun/" . $runId . "'>");
                                            echo("<span class='label label-warning'>rerun</span>");
                                            echo("</a>");
                                        } else if ($status === 'failed') {
                                            echo("<a href=\"javascript:void(0);\" onclick=\"window.open('http://'+location.host.replace(location.port,3000)+'/runs/" . $runId . "');\">");
                                            echo("<span class='label label-danger'>failed</span>");
                                            echo("</a>");
                                            echo("<a href='./competition-rerun/" . $runId . "'>");
                                            echo("<span class='label label-warning'>rerun</span>");
                                            echo("</a>");
                                        } else if ($runJson == '') {
                                            echo("<span class='label'>N/A</span>");
                                        }

                                        echo("</td>");
                                    } else {
                                        echo("<td>");
                                        echo("<span class='label label-info'>pending</span>");
                                        echo("<a href='./competition-repost/" . $session["name"] . "/" . $map['name'] . "/" . $agent['name'] . "'>");
                                        echo("<span class='label label-warning'>repost</span>");
                                        echo("</a>");
                                        echo("</td>");
                                    }
                                } else {
                                    echo("<td><span class='label label-default'>invalid</span></td>");
                                }
                            }
                        }
                        ?>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <form action="./competition-add_map" method="post">
                <input type="hidden" name="parameter_session" value="<?= $session["name"] ?>">
                <div class="input-group pull-right">
                    <select class="form-control" name="parameter_map">
                        <?php
                        foreach ($maps as $map) {
                            if (in_array($map['name'], array_column($session['maps'], 'name'))) {
                                continue;
                            }
                            ?>
                            <option value="<?= $map['name'] ?>"><?= $map['alias'] ?> : <?= $map['name'] ?>
                                (<?= $map['timestamp'] ?>)
                            </option>
                        <?php } ?>
                    </select>
                    <span class="input-group-btn"> <input class="btn btn-primary" type="submit" value="Add"> </span>
                </div>
            </form>
        </div>
    </div>
    <!-- /.box -->

<?php } ?>

<script>

</script>
