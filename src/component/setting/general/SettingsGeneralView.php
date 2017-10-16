<?php
use rrsoacis\system\Config;
use rrsoacis\manager\AccessManager;
?>
<!DOCTYPE html>
<html>
<head>
<?php $title="General"; ?>
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
          General
          <small>Settings</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i><?= rrsoacis\system\Config::APP_NAME ?></a></li>
        <li class=""><a href="/settings">Settings</a></li>
        <li class="active">General</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Version -->
        <div class="box box-solid" id="version-box">
            <div class="box-header">
                <h3 class="box-title">
                    Version
                </h3>
                <div class="box-tools">
                    <button class="btn">
                        <i class="fa fa-refresh fa-spin"></i> Loading...
                    </button>
                </div>
                <div class="box-body">
                    <b> Current version </b>
                    <pre>Loading...



                    </pre>

                    <b> Latest version </b>
                    <pre>loading...



                    </pre>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            simpleimport("version-box","/settings-version_content");
        </script>
        <!-- /Version -->

        <!-- Restrict Access -->
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">
                    Restrict Access
                </h3>
                <div class="box-tools">
                    <?php if (AccessManager::filterEnabled()) { ?>
                        <button class="btn btn-warning" onclick="location.href='<?=Config::$TOP_PATH ?>/settings-restrict_set/0'">Disable</button>
                    <?php } else { ?>
                        <button class="btn btn-success" onclick="location.href='<?=Config::$TOP_PATH ?>/settings-restrict_set/1'">Enable</button>
                    <?php } ?>
                </div>
            <div class="box-body">
                <b>
                    Unrestrict hosts
                </b>
                <small>
                    Current Host: <?=getenv("REMOTE_ADDR") ?>
                </small>
                <form action="./settings-restrict_set_unrestrected" method="post">
                    <div class="input-group pull-right">
                        <input class="form-control" name="hosts" type="text" value="<?=AccessManager::getUnrestrictedHostsText() ?>">
                        <span class="input-group-btn"> <input class="btn" type="submit" value="Set"> </span>
                    </div>
                </form>
            </div>
        </div>
        <!-- /Restrict Access -->
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
