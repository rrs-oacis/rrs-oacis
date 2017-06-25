<?php
use adf\Config;
?>
<!DOCTYPE html>
<html>
<head>
<?php $title="Settings"; ?>
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
        <a href="/settings-apps"><button class="btn-block btn">Apps</button></a><br>
        <a href="/settings-clusters"><button class="btn-block btn">Clusters</button></a><br>
        <a href="/settings-restrict"><button class="btn-block btn">Restrict access</button></a><br>
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
