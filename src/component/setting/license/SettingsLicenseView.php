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

            <?php for ($i=0;$i< count($license); $i++){ ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $license[$i]['name'] ?>
                        <small><?= $license[$i]['location']?></small>
                    </h3>
                    <div class="box-body">

                        <pre><?= file_get_contents(Config::$SRC_REAL_URL.'component/setting/license/license_txt/'.$license[$i]['license']);?>
                        </pre>

                    </div>
                </div>
            </div>

            <?php  } ?>

            <!-- /Version -->

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
