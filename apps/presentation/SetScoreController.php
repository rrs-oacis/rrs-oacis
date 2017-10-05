<?php

namespace rrsoacis\apps\presentation;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;

use rrsoacis\apps\competition\SessionManager;
use rrsoacis\manager\AgentManager;

class SetScoreController extends AbstractController {
	
	public function post() {
		
		ini_set ( 'display_errors', 1 );
		
		echo $_POST['session'] . '<br />';
		print_r($_POST);
		
		$session = $_POST['session'];
		$teamScores = [];
	
		foreach ($_POST as $key => $value) {
			$name = base64_decode($key);
			$agent = AgentManager::getAgentByAlias($name);
			if(count($agent)<=0) { continue; }
			if (!isset($value) || $value<=0) { continue; }
			$teamScores[$name] = $value;		
		}
		SessionManager::addPresentation($teamScores, $session);

		header('location: '.Config::$TOP_PATH.'presentation');
	}
}
