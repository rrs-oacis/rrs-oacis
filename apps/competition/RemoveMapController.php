<?php

namespace rrsoacis\apps\competition;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;

class RemoveMapController extends AbstractController
{
    public function post()
    {
	$sessionName = $_POST['parameter_session'];
        $mapName = $_POST['parameter_map'];

	SessionManager::removeMap($sessionName, $mapName);
		
        header('location: '.Config::$TOP_PATH.'competition');
    }
}
