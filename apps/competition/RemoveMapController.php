<?php

namespace adf\apps\competition;

use adf\Config;
use adf\controller\AbstractController;

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
