<?php
use rrsoacis\system\Config;
?>
<!DOCTYPE html>
<html>
<head>
<?php $title="Apps"; ?>
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
          <?= $cluster["name"] ?>
          <small>Clusters setting</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i><?= rrsoacis\system\Config::APP_NAME ?></a></li>
        <li class=""><a href="/settings">Settings</a></li>
        <li class=""><a href="/settings-clusters">Clusters</a></li>
        <li class="active"><?= $cluster["name"] ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div style="margin-bottom: 2em;">
            <div>
                <a href="<?=Config::$TOP_PATH ?>/settings-cluster_remove/<?= $cluster["name"] ?>">
                    <button class="btn btn-danger">Remove</button>
                </a>
            </div>
        </div>

        <!-- BEGIN : PublicKey -->
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
                <textarea class="form-control" rows="8" readonly><?= \rrsoacis\manager\ClusterManager::getClusterRawCheckMessage($cluster["name"]) ?>
                </textarea>
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

<script>
</script>
</body>