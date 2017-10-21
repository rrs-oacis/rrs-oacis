<?php
namespace rrsoacis\component\dashboard;

use rrsoacis\component\common\AbstractPage;
use rrsoacis\system\Config;

class RestrictedDashboardPage extends AbstractPage
{
    public function controller($params)
    {
        $this->setTitle("Index");
        $this->printPage();
	}

	function body()
    {
        self::writeContentHeader("Dashboard");

        self::beginContent();
        self::endContent();
    }

    function sideber()
    {
        ?>
        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
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
        <?php
    }
}
