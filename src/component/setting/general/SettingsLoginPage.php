<?php
namespace rrsoacis\component\setting\general;

use rrsoacis\component\common\AbstractPage;
use rrsoacis\manager\AccessManager;
use rrsoacis\system\Config;

class SettingsLoginPage extends AbstractPage
{
    public function controller($params)
    {
        $this->setTitle("Login");

        if (isset($_POST["password"]))
        {
            $sessionId = AccessManager::getSessionId($_POST["password"]);
            if ($sessionId !== "")
            {
                setcookie("roid", $sessionId);
                header('location: '.Config::$TOP_PATH);
                return;
            }
            else
            {
                header('location: '.Config::$TOP_PATH.'settings-login');
            }
        }
        else
        {
            $this->body();
        }

	}

	function body()
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <?php $title="General"; ?>
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
                <li class="active">Login</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="login-box">
                <!-- /.login-logo -->
                <div class="login-box-body">
                    <form action="<?=Config::$TOP_PATH ?>settings-login" method="post">
                        <div class="form-group has-feedback">
                            <input type="password" class="form-control" placeholder="Password" name="password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="row">
                            <div class="col-xs-8">
                            </div>
                            <!-- /.col -->
                            <div class="col-xs-4">
                                <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                </div>
                <!-- /.login-box-body -->
            </div>
            <!-- /.login-box -->
        </section>
        <!-- /.content -->

        <!-- =============================================== -->

        <?php include Config::$SRC_REAL_URL . 'component/common/main-footer.php';?>

        <!-- =============================================== -->


        <?php include Config::$SRC_REAL_URL . 'component/common/footerscript.php';?>

        </body>
        </html>
        <?php
    }
}
