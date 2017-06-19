<?php
use adf\Config;
?>
<!DOCTYPE html>
<html>
<head>
<?php $title="Apps"; ?>
<?php include 'component/head.php';?>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <?php include 'component/main-header.php';?>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <?php include 'component/main-sidebar.php';?>
  
  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
          <?= $app["name"] ?>
          <small>Apps setting</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i><?= adf\Config::APP_NAME ?></a></li>
        <li class=""><a href="/settings">Settings</a></li>
        <li class=""><a href="/settings-apps">Apps</a></li>
        <li class="active"><?= $app["name"] ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Apps</h3>
                        <h3 class="">Apps</h3>
                        <div class="box-tools">
                            <?php if ($app["enabled"]) { ?>
                                <a href="<?=Config::$TOP_PATH ?>/settings-app_enable/<?= $app["package"] ?>/0">
                                    <button class="btn btn-warning">Disable</button>
                                </a>
                            <?php } else { ?>
                                <a href="<?=Config::$TOP_PATH ?>/settings-app_enable/<?= $app["package"] ?>/1">
                                    <button class="btn btn-success">Enable</button>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>Name</th>
                                <th>Ver.</th>
                                <th>Description</th>
                                <th>Status</th>
                            </tr>
                                <tr>
                                    <td><?= $app["name"]?></td>
                                    <td><?= $app["version"]?></td>
                                    <td><?= $app["description"]?></td>
                                    <td>
                                        <?php if ($app["enabled"]) { ?>
                                            <span class="label label-success">Enabled</span>
                                        <?php } else { ?>
                                            <span class="label label-warning">Disabled</span>
                                        <?php } ?>
                                    </td>
                                </tr>



                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- =============================================== -->

  <?php include 'component/main-footer.php';?>
  
  <!-- =============================================== -->

</div>
<!-- ./wrapper -->

<?php include 'component/footerscript.php';?>

<script>
</script>
</body>
