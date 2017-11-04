<?php
use rrsoacis\system\Config;
?>
<!DOCTYPE html>
<html>
<head>
<?php $title="MapsList"; ?>
<?php include Config::$SRC_REAL_URL . 'component/common/head.php';?>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <?php include Config::$SRC_REAL_URL . 'component/common/main-header.php';?>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <?php include Config::$SRC_REAL_URL . 'component/common/main-sidebar.php';?>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
          Maps
        <small></small>
      </h1>
      <ol class="breadcrumb">
          <li><a href="/"><i class="fa fa-dashboard"></i> Index</a></li>
          <li class="active">Maps</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

		<!-- エージェントリストのbox  -->
		<?php include 'box-add_map.php';?>
        <?php include 'box-maplist.php';?>

        <!-- Deprecated  -->
        <?php //include 'box-maparchivedlist.php';?>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- =============================================== -->

  <?php //include Config::$SRC_REAL_URL . 'component/common/main-footer.php';?>

    <footer class="main-footer">
      <!--
        <a href="/maps_archive">
            <i class="fa fa-archive"></i> Archived Maps
        </a>
        -->

        <div class="pull-right hidden-xs">
            <b>Version</b> <?= Config::APP_VERSION ?>
        </div>
        <br>
    </footer>



  <!-- =============================================== -->

</div>
<!-- ./wrapper -->

<?php include Config::$SRC_REAL_URL . 'component/common/footerscript.php';?>
<!-- SlimScroll -->
<script src="./plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE for demo purposes -->

<!-- <script src="./dist/js/demo.js"></script> -->

<script>
$(".readonly").keydown(function(e){
    e.preventDefault();
});
  $('input[id=lefile]').change(function() {
    $('#photoCover').val($(this).val());
  });
</script>
</body>
