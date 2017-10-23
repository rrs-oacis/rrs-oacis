<?php
namespace rrsoacis\component\setting\app;

use rrsoacis\component\common\AbstractPage;
use rrsoacis\manager\AppManager;
use rrsoacis\system\Config;

class SettingsAppInstallerPage extends AbstractPage
{
    public function controller($params)
    {
        $this->setTitle("App Installer");
        if (isset($_POST['name']) && isset($_POST['user']))
        {
            $packageName = str_replace("-", "_", $_POST['user']).'/'.$_POST['name'];
            $app = AppManager::getApp($packageName);
            if ($app == null) {
                $installResult = AppManager::installPackage($packageName);
                if ($installResult === "") {
                    header('location: ' . Config::$TOP_PATH . 'settings-app/' . $packageName);
                    exit();
                } else {
                    header('location: ' . Config::$TOP_PATH . 'settings-app_installer');
                    exit();
                }
            } else if ($app['is_plugin']) {
                exec("timeout 5 ping 8.8.8.8 -c 1", $exec_out, $internet);
                $internet = ($internet == 0);
                if ($internet) {
                    exec("cd ".Config::$SRC_REAL_URL."apps/".$packageName."; git reset --hard HEAD; git pull");
                    header('location: ' . Config::$TOP_PATH . 'settings-app/' . $packageName);
                }
            }
        }
        $this->printPage();
	}

	function body()
    {
        self::writeContentHeader("App Installer", "",
            array('<a href="/settings">Settings</a>','<a href="/settings-apps">Apps</a>'));

        self::beginContent();
        ?>
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Add App</h3>
                <div class="box-tools pull-right">
                </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form id="post-form" action="./settings-app_installer" method="POST" class="form-horizontal">
                <div class="box-body">
                    <div class="form-group">
                        <label for="inputUser" class="col-sm-1 control-label">Name</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="user"
                                   id="inputUser" placeholder="user" required>
                        </div>
                        <label for="inputName" class="col-sm-1 control-label" style="text-align: center;">/</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="name"
                                   id="inputName" placeholder="name" required>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-info pull-right">Install</button>
                </div>
                <!-- /.box-footer -->
            </form>
        </div>
        <!-- /.box -->
        <?php
        self::endContent();
    }

    function footer()
    {
        ?>
        <footer class="main-footer">
            <a href="/settings-license">
                <!-- Display on only settings page -->
                <i class="fa fa-book"></i> LicenseTerms
            </a>
            <div class="pull-right hidden-xs">
                <b>Version</b> <?= Config::APP_VERSION ?>
            </div>
            <br>
        </footer>
        <!-- =============================================== -->
        </div>
        <!-- ./wrapper -->
        <?php include Config::$SRC_REAL_URL . 'component/common/footerscript.php';?>
        </body>
        </html>
        <?php
    }
}
