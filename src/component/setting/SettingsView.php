<?php
use rrsoacis\system\Config;
?>
<!DOCTYPE html>
<html>
<head>
<?php $title="Settings"; ?>
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
      <h1>Settings
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i> RRS-OACIS</a></li>
        <li class="active">Settings</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	<div class="row">

	<div class="col-md-3 col-sm-6 col-xs-12">
        <a href="/settings-general">
	<div class="info-box">
	<span class="info-box-icon bg-green"><i class="fa fa-cogs"></i></span>
	<div class="info-box-content">
	<span class="info-box-text">General</span>
	</div>
	<!-- /.info-box-content -->
	</div>
	<!-- /.info-box -->
	</a>
	</div>
	<!-- /.col -->

	<div class="col-md-3 col-sm-6 col-xs-12">
        <a href="/settings-apps">
	<div class="info-box">
	<span class="info-box-icon bg-red"><i class="fa fa-window-restore"></i></span>
	<div class="info-box-content">
	<span class="info-box-text">Apps</span>
	</div>
	<!-- /.info-box-content -->
	</div>
	<!-- /.info-box -->
	</a>
	</div>
	<!-- /.col -->

	<div class="col-md-3 col-sm-6 col-xs-12">
	<a href="/settings-clusters">
	<div class="info-box">
	<span class="info-box-icon bg-aqua"><i class="fa fa-server"></i></span>
	<div class="info-box-content">
	<span class="info-box-text">Clusters</span>
	</div>
	<!-- /.info-box-content -->
	</div>
	<!-- /.info-box -->
	</a>
	</div>
	<!-- /.col -->

	</div>
	<!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- =============================================== -->

    <footer class="main-footer">
        <a href="/settings-license">
            <!-- Display on only settings page -->
            <i class="fa fa-book"></i> LicenseTerms
        </a>
        <div class="pull-right hidden-xs">
            <b>Version</b> <?= Config::APP_VERSION ?>
        </div>
        <br>
    </footer>

  <!-- =============================================== -->

</div>
<!-- ./wrapper -->

<?php include Config::$SRC_REAL_URL.'component/common/footerscript.php';?>

<script>
</script>
</body>
