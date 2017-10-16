<?php
use rrsoacis\system\Config;
?>
<!-- BEGIN : Clusters -->
<!--<div class="row">-->
    <div class="col-xs-12">
        <input type="hidden" id="clusters-list-widget-needs-refresh" value="<?= $needsRefresh ?>">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Clusters</h3>
                <div class="box-tools">
                    <button class="btn btn-info" onclick="location.href='<?= Config::$TOP_PATH."settings-cluster_statusupdate" ?>'">Update</button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tr>
                        <th>Name</th>
                        <th>Host-S</th>
                        <th>Host-A</th>
                        <th>Host-F</th>
                        <th>Host-P</th>
                    </tr>
                    <?php foreach ($clusters as $cluster) {?>
                        <tr class="linked-row" data-href="<?= Config::$TOP_PATH."settings-cluster/".$cluster["name"] ?>">
                            <th style="color:<?= ($cluster["check_status"]==1 ? "gray" : ($cluster["check_status"]==0 ? "green" : "red")) ?>;"><?= $cluster["name"]?></th>
                            <td><?= $cluster["s_host"]?></td>
                            <td><?= $cluster["a_host"]?></td>
                            <td><?= $cluster["f_host"]?></td>
                            <td><?= $cluster["p_host"]?></td>
                        </tr>
                    <?php }?>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
<!--</div>-->
<!-- END : Clusters -->
