<?php
use rrsoacis\system\Config;
?>
<!--<section class="content" id="main-content">-->
<div style="margin-bottom: 2em;">
    <div>
        <a href="<?=Config::$TOP_PATH ?>/settings-cluster_remove/<?= $cluster["name"] ?>">
            <button class="btn btn-danger">Remove</button>
        </a>
    </div>
</div>

<!-- BEGIN : Clusters -->
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Clusters</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tr>
                        <th></th>
                        <th>Host</th>
                        <th>javac</th>
                    </tr>
                    <tr>
                        <th style="color:<?= ($hasError["S"]?"red":"green") ?>;">Server</th>
                        <td><?= $cluster["s_host"]?></td>
                        <td><?= $javaVer["S"]?></td>
                    </tr>
                    <tr>
                        <th style="color:<?= ($hasError["A"]?"red":"green") ?>;">Ambulance</th>
                        <td><?= $cluster["a_host"]?></td>
                        <td><?= $javaVer["A"]?></td>
                    </tr>
                    <tr>
                        <th style="color:<?= ($hasError["F"]?"red":"green") ?>;">Fire</th>
                        <td><?= $cluster["f_host"]?></td>
                        <td><?= $javaVer["F"]?></td>
                    </tr>
                    <tr>
                        <th style="color:<?= ($hasError["P"]?"red":"green") ?>;">Police</th>
                        <td><?= $cluster["p_host"]?></td>
                        <td><?= $javaVer["P"]?></td>
                    </tr>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
<!-- END : Clusters -->

<!-- BEGIN : Setup -->
<div class="row">
    <div class="col-xs-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Setup
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form id="post-form" action="<?= Config::$TOP_PATH."settings-cluster_update" ?>" method="POST" class="form-horizontal">
                    <div class="input-group pull-right">
                        <input type="hidden" name="name" value="<?= $cluster["name"] ?>">
                        <input type="hidden" name="s_host" value="<?= $cluster["s_host"] ?>">
                        <input type="hidden" name="a_host" value="<?= $cluster["a_host"] ?>">
                        <input type="hidden" name="f_host" value="<?= $cluster["f_host"] ?>">
                        <input type="hidden" name="p_host" value="<?= $cluster["p_host"] ?>">
                        <input class="form-control" placeholder="Hosts password" name="hosts_pass" type="password" value="">
                        <span class="input-group-btn"> <input class="btn" type="submit" value="Run"> </span>
                    </div>
                </form>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
<!-- END : Setup -->

<!-- BEGIN : Check Message -->
<div class="row">
    <div class="col-xs-12">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Check Massage
                    <small>Raw outputs of cluster checker</small>
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <textarea class="form-control" rows="8" readonly><?= $checkMessage ?></textarea>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
<!-- END : Check Message -->
<!--</section>-->
<!-- /.content -->
