<?php
namespace rrsoacis\component\dashboard;

use rrsoacis\component\common\AbstractPage;
use rrsoacis\system\Config;

class DashboardPage extends AbstractPage
{
    public function controller()
    {
        $this->setTitle("Index");
        $this->printPage();
	}

	function body()
    {
        self::writeContentHeader("Dashboard", "",
            array(
                '<a href="<?= Config::$TOP_PATH ?>"><i class="fa fa-dashboard"></i>RRS-OACIS</a>',
                'Dashboard'));

        self::beginContent();
        include Config::$SRC_REAL_URL .'component/agent/box-agentlist.php';
        include Config::$SRC_REAL_URL. 'component/map/box-maplist.php';
        self::endContent();
    }
}
