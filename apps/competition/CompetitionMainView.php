<?php
ini_set('display_errors', 0);
use adf\Config;
?>
<!DOCTYPE html>
<html>
<head>
<?php $title="Apps"; ?>
<?php include Config::$SRC_REAL_URL.'view/component/head.php';?>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <?php include Config::$SRC_REAL_URL.'view/component/main-header.php';?>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <?php include Config::$SRC_REAL_URL.'view/component/main-sidebar.php';?>
  
  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
          Competition
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i><?= adf\Config::APP_NAME ?></a></li>
        <li class="active">Competition</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php include 'box-add_session.php';?>

        <!-- BEGIN : Sessions -->
        <?php foreach ($sessions as $session) {?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">
                                <?= $session["alias"]?>
                                <small><?= $session["name"]?></small>
                            </h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr >
                                    <th>Agent＼Map</th>
                                    <?php
                                    if (count($session['maps']) <= 0)
                                    {
                                        echo ("<th>(no map)</th>");
                                    }
                                    else
                                    {
                                        foreach ($session['maps'] as $map)
                                        {
                                    ?>
                                        <th title="<?= $map['name']?> (<?= $map['timestamp']?>)"><?= $map['alias']?>
<form method="post" action="./competition-remove_map" style="display:inline;">
<input type="hidden" name="parameter_session" value="<?= $session["name"]?>">
<input type="hidden" name="parameter_map" value="<?= $map['name']?>">
                                    <input class="btn-xs btn-warning" type="submit" value="cancel">
</form>
                                        </th>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php foreach ($session['agents'] as $agent) {?>
                                    <tr class="linked-rowstop" data-href="<?= Config::$TOP_PATH."settings-clusters" ?>">
                                        <?php if (isset($agent['name'])) { ?>
                                            <th title="<?= $agent['name']?> (<?= $agent['timestamp']?>)"><?= $agent['alias']?></th>
                                        <?php } else { ?>
                                            <th style="color: silver;"><?= $agent['alias']?></th>
                                        <?php } ?>
                                        <?php
                                        if (count($session['maps']) <= 0)
                                        {
                                            echo ("<td></td>");
                                        }
                                        else
                                        {
                                            foreach ($session['maps'] as $map)
                                            {
                                                $runId = $session['runs'][$map['name']][$agent['name']]['runId'];

                                                if (isset($agent['name']))
                                                {
                                                    if ($runId != '')
                                                    {
                                                        $runJson = '';
                                                        $runJson = file_get_contents('http://localhost:3000/runs/'.$runId.'.json');
                                                        $run = json_decode($runJson, true);
                                                        $status = $run['status'];

                                                        echo ("<td>");

							echo ("<a href=\"javascript:void(0);\" onclick=\"window.open('http://'+location.host.replace(location.port,3000)+'/runs/".$runId."');\">");
                                                        if ($status === 'running' || $status === 'submitted')
                                                        {
                                                            echo ("<span class='label label-primary'>running</span>");
                                                        }
                                                        else if ($status === 'created')
                                                        {
                                                            echo ("<span class='label label-warning'>reserved</span>");
                                                        }
                                                        else if ($status === 'finished')
                                                        {
                                                            echo ("<span class='label label-success'>finished</span>");
                                                        }
                                                        else if ($status === 'failed')
                                                        {
                                                            echo ("<span class='label label-danger'>failed</span>");
                                                        }
                                                        else if ($runJson == '')
                                                        {
                                                            echo ("<span class='label label-danger'>FAILED</span>");
                                                        }

                                                        echo ("</a>");
                                                        echo ("</td>");
                                                    }
                                                    else
                                                    {
                                                        echo ("<td><span class='label label-info'>pending</span></td>");
                                                    }
                                                }
                                                else
                                                {
                                                    echo ("<td><span class='label label-default'>invalid</span></td>");
                                                }
                                            }
                                        }
                                        ?>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <form action="./competition-add_map" method="post">
                                <input type="hidden" name="parameter_session" value="<?= $session["name"]?>">
                                <div class="input-group pull-right">
                                    <select class="form-control" name="parameter_map">
                                        <?php
                                        foreach ($maps as $map)
                                        {
                                            if (in_array($map['name'], array_column($session['maps'], 'name')))
                                            { continue; }
                                            ?>
                                            <option value="<?= $map['name']?>"><?= $map['alias']?> : <?= $map['name']?> (<?= $map['timestamp']?>) </option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-btn"> <input class="btn" type="submit" value="Add"> </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        <?php }?>
        <!-- END : Sessions -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- =============================================== -->

  <?php include Config::$SRC_REAL_URL.'view/component/main-footer.php';?>
  
  <!-- =============================================== -->

</div>
<!-- ./wrapper -->

<?php include Config::$SRC_REAL_URL.'view/component/footerscript.php';?>

</body>
