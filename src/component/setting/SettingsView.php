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
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- =============================================== -->


  <!-- =============================================== -->

</div>
<!-- ./wrapper -->

<?php include Config::$SRC_REAL_URL.'component/common/footerscript.php';?>

<script>
</script>
</body>
