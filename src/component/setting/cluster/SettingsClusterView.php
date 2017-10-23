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
    <section class="content" id="main-contents">
    </section>
      <script type="text/javascript">
          var refreshContents = function () {
              document.getElementById("main-contents").innerHTML = ' <div style="margin-bottom: 2em;"> <div>'
                  + '<button class="btn btn-danger" '
                  + 'onclick="location.href=\'/settings-cluster_remove/'
                  + '<?= $cluster["name"] ?>\'">Remove</button> &nbsp; '
                <?php if ($cluster["check_status"] == 3) { ?>
                  + '<button class="btn btn-success" '
                  + 'onclick="location.href=\'/settings-cluster_enable/'
                  + '<?= $cluster["name"] ?>/1\'">Enable</button>'
                <?php } else { ?>
                  + '<button class="btn btn-warning" '
                  + 'onclick="location.href=\'/settings-cluster_enable/'
                  + '<?= $cluster["name"] ?>/0\'">Disable</button>'
                <?php } ?>
                  + '</div> </div> <h1 class="text-center"> <i class="fa fa-refresh fa-spin"></i> </h1>';
              simpleimport("main-contents","/settings-cluster_contents/<?= $clusterName ?>");
          }
          refreshContents();
      </script>
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
