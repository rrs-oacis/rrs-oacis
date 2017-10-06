<?php
use rrsoacis\system\Config;
?>
<!DOCTYPE html>
<html>
<head>
<?php $title="400"; ?>
<?php include Config::$SRC_REAL_URL . 'component/common/head.php';?>

</head>
<body class="transition skin-blue">
    <!-- Logo -->
    <header class="main-header">
        <a href="<?=Config::$TOP_PATH ?>" class="logo" style="width:100%;">
            <span class="logo-lg"><?= Config::APP_NAME ?></span>
        </a>
    </header>
    <div class="clearfix"></div>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="<?=Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i> RRS-OACIS</a></li>
        <li class="active">400</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

		<div class="error-page">
        <h2 class="headline text-red"> 400</h2>

        <div class="error-content">
          <!-- <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3> -->
            <h3><i class="fa fa-warning text-red"></i> Oops! Something went wrong.</h3>

          <p>
              Your request cannot be processed.

            Meanwhile, you may <a href="<?=Config::$TOP_PATH ?>">return to dashboard</a>.
          </p>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
    <!-- /.content -->

  <!-- =============================================== -->

  <?php include Config::$SRC_REAL_URL . 'component/common/main-footer.php';?>
  
  <!-- =============================================== -->


<?php include Config::$SRC_REAL_URL . 'component/common/footerscript.php';?>

</body>