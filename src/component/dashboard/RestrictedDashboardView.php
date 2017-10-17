<?php
use rrsoacis\system\Config;
?>
<!DOCTYPE html>
<html>
<head>
<?php $title="Index"; ?>
<?php include Config::$SRC_REAL_URL . 'component/common/head.php';?>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <?php include Config::$SRC_REAL_URL . 'component/common/main-header.php';?>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- search form -->
            <!--
            <form action="./search.php" method="get" class="sidebar-form">
              <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="検索..."> <span class="input-group-btn">
                  <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                    <i class="fa fa-search"></i>
                  </button>
                </span>
              </div>
            </form>
             -->
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <!-- <li class="header">MAIN NAVIGATION</li> -->
                <li><a href="<?=Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                <?php
                /*foreach (AppManager::getConnectedApps() as $appDisplayOnSidebar) {
                    echo '<li><a href="' . Config::$TOP_PATH . $appDisplayOnSidebar['package'] . '"><i class="fa ' . $appDisplayOnSidebar['icon'] . '"></i> <span>' . $appDisplayOnSidebar['name'] . '</span></a></li>';
                }
                */ ?>
                <li><a href="<?=Config::$TOP_PATH ?>settings-login"><i class="fa fa-lock"></i> <span>Login</span></a></li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
  
  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
          Dashboard
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i>RRS-OACIS</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

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
<!-- SlimScroll -->
<script src="./plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./plugins/fastclick/fastclick.js"></script>


<script>
</script>
</body>
