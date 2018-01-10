<?php
namespace rrsoacis\component\setting\general;

use manager\CoreManager;
use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;

class SettingsVersionUpdateController extends AbstractController
{
	public function get()
	{
		CoreManager::update();
		header('location: '.Config::$TOP_PATH.'settings-general');
	}
}
