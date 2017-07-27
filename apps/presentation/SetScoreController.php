<?php

namespace adf\apps\presentation;

use adf\Config;
use adf\controller\AbstractController;

use adf\apps\competition\SessionManager;
use adf\file\AgentLoader;

class SetScoreController extends AbstractController {
	
	public function post() {
		
		ini_set ( 'display_errors', 1 );
		
		echo $_POST['session'] . '<br />';
		print_r($_POST);
		
		$session = $_POST['session'];
		$teamScores = [];
	
		foreach ($_POST as $key => $value) {
			$name = base64_decode($key);
			$agent = AgentLoader::getAgentByAlias($name);
			if(count($agent)<=0) { continue; }
			if (!isset($value) || $value<=0) { continue; }
			$teamScores[$name] = $value;		
		}
		SessionManager::addPresentation($teamScores, $session);

		header('location: '.Config::$TOP_PATH.'presentation');
	}
}
