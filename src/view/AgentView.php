<?php
use adf\Config;
?>
<!DOCTYPE html>
<html>
<head>
<?php $title="AgentList"; ?>
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
        <small><?= _l("adf.operation_screen"); ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i> Index</a></li>
        <li><a href="<?=Config::$TOP_PATH ?>agents"><i class="fa fa-dashboard"></i> <?= _l("adf.agent_list"); ?></a></li>
        <li class="active"><?= $agent["name"] ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

		<!-- エージェントリストのbox  -->
    <?php include 'component/box-agent.php';?>

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
$(".readonly").keydown(function(e){
    e.preventDefault();
});
  $('input[id=lefile]').change(function() {
    $('#photoCover').val($(this).val());
  });
</script>
</body>