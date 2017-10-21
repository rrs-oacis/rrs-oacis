<?php
namespace rrsoacis\component\setting\license;

use rrsoacis\component\common\AbstractPage;
use rrsoacis\system\Config;

class SettingsLicensePage extends AbstractPage
{
    private $license;
    public function controller($params)
    {
        $this->setTitle("License");
        $license = file_get_contents(Config::$SRC_REAL_URL.'component/setting/license/license.json');
        $license = mb_convert_encoding($license, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $license = json_decode($license, true);
        $this->license = $license;
        $this->printPage();
	}

	function body()
    {
        $license = $this->license;

        self::writeContentHeader("License terms", "",
            array('<a href="/settings">Settings</a>'));

        self::beginContent();
        ?>
        <div class="box box-success">
            <pre><?= file_get_contents(Config::$SRC_REAL_URL.'../LICENSE');?></pre>
        </div>

        <h4>Used libraries</h4>

        <?php for ($i=0;$i< count($license); $i++){ ?>
        <div class="box collapsed-box">
            <div class="box-header">
                <h3 class="box-title">
                    <?= $license[$i]['name'] ?>
                    <small><?= $license[$i]['location']?></small>
                </h3>
                <div class="box-tools pull-right">
                    <!-- Collapse Button -->
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>

            </div>
            <div style="display: none;" class="box-body">

                        <pre><?= file_get_contents(Config::$SRC_REAL_URL.'component/setting/license/license_txt/'.$license[$i]['license']);?>
                        </pre>

            </div>
        </div>
        <?php  } ?>

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
