<?php
use adf\Config;
use adf\manager\AccessManager;
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
          Restrict Access
          <small>Settings</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i><?= adf\Config::APP_NAME ?></a></li>
        <li class=""><a href="/settings">Settings</a></li>
        <li class="active">Restrict Access</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">
                    Restrict Access
                </h3>
            </div>
            <div class="box-body">
                <div class="pull-right">
                    <?php if (AccessManager::filterEnabled()) { ?>
                        <a href="<?=Config::$TOP_PATH ?>/settings-restrict_set/0">
                            <button class="btn btn-warning">Disable</button>
                        </a>
                    <?php } else { ?>
                        <a href="<?=Config::$TOP_PATH ?>/settings-restrict_set/1">
                            <button class="btn btn-success">Enable</button>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">
                    Unrestrict hosts
                    <small>
                        Current Host: <?=getenv("REMOTE_ADDR") ?>
                    </small>
                </h3>
            </div>
            <div class="box-body">
                <form action="./settings-restrict_set_unrestrected" method="post">
                    <div class="input-group pull-right">
                        <input class="form-control" name="hosts" type="text" value="<?=AccessManager::getUnrestrictedHostsText() ?>">
                        <span class="input-group-btn"> <input class="btn" type="submit" value="Set"> </span>
                    </div>
                </form>
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
