<?php
namespace rrsoacis\component\agent;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;

class AgentListController extends AbstractController
{
	public function get()
    {
		include (Config::$SRC_REAL_URL . 'component/agent/AgentListView.php');
	}
}