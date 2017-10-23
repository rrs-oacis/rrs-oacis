<?php
use rrsoacis\system\Config;
?>
<!DOCTYPE html>
<html>
<head>
    <?php $title="AgentList"; ?>
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
                <?= $map["name"] ?>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?=Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i> Index</a></li>
                <li><a href="<?=Config::$TOP_PATH ?>maps"><i class="fa fa-map"></i>Map List</a></li>
                <li class="active"><?= $map["name"] ?></li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- エージェントリストのbox  -->
            <?php include 'box-map.php';?>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- =============================================== -->

    <?php include Config::$SRC_REAL_URL . 'component/common/main-footer.php';?>

    <!-- =============================================== -->

</div>
<!-- ./wrapper -->

<?php include Config::$SRC_REAL_URL . 'component/common/footerscript.php';?>

<script>
</script>
</body>