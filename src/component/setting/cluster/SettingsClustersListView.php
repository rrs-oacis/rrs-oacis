<?php
use rrsoacis\system\Config;
?>
<!DOCTYPE html>
<html>
<head>
<?php $title="Clusters"; ?>
<?php include Config::$SRC_REAL_URL.'component/common/head.php';?>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <?php include Config::$SRC_REAL_URL.'component/common/main-header.php';?>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <?php include Config::$SRC_REAL_URL.'component/common/main-sidebar.php';?>
  
  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Clusters
        <small>Settings</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i><?= rrsoacis\system\Config::APP_NAME ?></a></li>
        <li class=""><a href="/settings">Settings</a></li>
        <li class="active">Clusters</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
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
                                <th>Name</th>
                                <th>Host-S</th>
                                <th>Host-A</th>
                                <th>Host-F</th>
                                <th>Host-P</th>
                            </tr>
                            <?php foreach ($clusters as $cluster) {?>
                                <tr class="linked-row" data-href="<?= Config::$TOP_PATH."settings-cluster/".$cluster["name"] ?>">
                                    <td><?= $cluster["name"]?></td>
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
        </div>
        <!-- END : Clusters -->

        <!-- BEGIN : Add cluster -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Add cluster</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form id="post-form" action="<?= Config::$TOP_PATH."settings-cluster_update" ?>" method="POST" class="form-horizontal">
                <div class="box-body">
                    <div class="form-group">
                        <label for="inputHostS" class="col-sm-2 control-label">Host-S</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="s_host"
                                   id="inputHostS"
                                   placeholder="user@host"
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputHostA" class="col-sm-2 control-label">Host-A</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="a_host"
                                   id="inputHostA"
                                   placeholder="user@host"
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputHostF" class="col-sm-2 control-label">Host-F</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="f_host"
                                   id="inputHostF"
                                   placeholder="user@host"
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputHostP" class="col-sm-2 control-label">Host-P</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="p_host"
                                   id="inputHostP"
                                   placeholder="user@host"
                                   required>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right">Add</button>
                </div>
                <!-- /.box-footer -->
                <input type="hidden" name="action" value="create">
            </form>
        </div>
        <!-- END : Add cluster -->

        <!-- BEGIN : PublicKey -->
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Public key
                    <small>paste to ~/.ssh/authorized_keys</small>
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <textarea class="form-control" rows="5" readonly><?php include(Config::$PUBLICKEY_PATH); ?></textarea>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- END : PublicKey -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- =============================================== -->

  <?php include Config::$SRC_REAL_URL.'component/common/main-footer.php';?>
  
  <!-- =============================================== -->

</div>
<!-- ./wrapper -->

<?php include Config::$SRC_REAL_URL.'component/common/footerscript.php';?>

</body>
