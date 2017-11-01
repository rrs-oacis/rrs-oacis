<?php
namespace rrsoacis\component\setting;

use rrsoacis\component\common\AbstractPage;
use rrsoacis\system\Config;

class SettingsPage extends AbstractPage
{
    public function controller($params)
    {
        $this->setTitle("Settings");
        $this->printPage();
	}

	function body()
    {
        self::writeContentHeader("Settings");

        self::beginContent();
        ?>
        <div class="row">

            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="/settings-general">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-cogs"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">General</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </a>
            </div>
            <!-- /.col -->

            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="/settings-apps">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-window-restore"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Apps</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </a>
            </div>
            <!-- /.col -->

            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="/settings-clusters">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-server"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Clusters</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </a>
            </div>
            <!-- /.col -->

        </div>
        <!-- /.row -->
        <?php
        self::endContent();
    }

    function footer()
    {
        ?>
        <footer class="main-footer">
            <a href="/settings-license">
                <!-- This link display on only settings page -->
                <i class="fa fa-book"></i> LicenseTerms
            </a>
					<a href="https://github.com/rrs-oacis/rrs-oacis" target="_blank" style="margin-left: 1em;">
						<!-- This link display on only settings page -->
						<i class="fa fa-github"></i> GitHub
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
