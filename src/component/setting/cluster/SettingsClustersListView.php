<?php
use rrsoacis\system\Config;
?>
<!DOCTYPE html>
<html>
<head>
<?php $title="Clusters"; ?>
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
        Clusters
        <small>Settings</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i><?= rrsoacis\system\Config::APP_NAME ?></a></li>
        <li class=""><a href="/settings">Settings</a></li>
        <li class="active">Clusters</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- BEGIN : Clusters -->
        <div class="row" id="cluster-list-widget"></div>
        <script type="text/javascript">
            var refreshClustersList = function () {
                simpleimport("cluster-list-widget","/settings-clusters_widget",function(){
                    var needsRefresh = document.getElementById("clusters-list-widget-needs-refresh").value;
                    if (needsRefresh == 1) { setTimeout(refreshClustersList, 3000); }
                    $(".linked-row").click(function() { location.href = $(this).data("href"); });
                });
            };
            refreshClustersList();
        </script>
        <!-- END : Clusters -->

        <!-- BEGIN : Add cluster -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Add cluster</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form id="post-form" action="<?= Config::$TOP_PATH."settings-cluster_update" ?>" method="POST" class="form-horizontal">
                <div class="box-body">
                    <div class="form-group">
                        <label for="inputHostS" class="col-sm-2 control-label">Host-S</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="s_host"
                                   id="inputHostS"
                                   placeholder="user@host"
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputHostA" class="col-sm-2 control-label">Host-A</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="a_host"
                                   id="inputHostA"
                                   placeholder="user@host"
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputHostF" class="col-sm-2 control-label">Host-F</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="f_host"
                                   id="inputHostF"
                                   placeholder="user@host"
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputHostP" class="col-sm-2 control-label">Host-P</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="p_host"
                                   id="inputHostP"
                                   placeholder="user@host"
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputArchiver" class="col-sm-2 control-label">Archiver</label>
                        <div class="col-sm-10">
                            <select id="inputArchiver" class="form-control" name="archiver">
                                <option value="gzip" selected>gzip (gzip + tar)</option>
                                <option value="7zip">p7zip (7za)</option>
                                <option value="zip">zip</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputHostsPass" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="hosts_pass"
                                   id="inputHostsPass"
                                   placeholder="Hosts' password (if hosts have diffrent password, input Host-S's password and setup other hosts at cluster page)"
                                   required>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right">Add</button>
                </div>
                <!-- /.box-footer -->
                <input type="hidden" name="action" value="create">
            </form>
        </div>
        <!-- END : Add cluster -->

        <!-- BEGIN : PublicKey -->
        <!--
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Public key
                    <small>paste to ~/.ssh/authorized_keys</small>
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="box-body">
                <textarea class="form-control" rows="5" readonly><?php include(Config::$PUBLICKEY_PATH); ?></textarea>
            </div>
        </div>
        -->
        <!-- END : PublicKey -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- =============================================== -->

  <?php include Config::$SRC_REAL_URL.'component/common/main-footer.php';?>
  
  <!-- =============================================== -->

</div>
<!-- ./wrapper -->

<?php include Config::$SRC_REAL_URL.'component/common/footerscript.php';?>

</body>
