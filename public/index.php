<?php 
ini_set ( 'display_errors', 1 );

require '../vendor/autoload.php';

use adf\Router;
use adf\Config;

//静的FileへのPath
$resourcePath = dirname($_SERVER["SCRIPT_NAME"]);
if(mb_substr(dirname($_SERVER["SCRIPT_NAME"]), -1)!="/"){
	$resourcePath = $resourcePath . "/";
}

Config::$RESOURCE_PATH = $_SERVER['HTTP_HOST'].$resourcePath;

//URLを元にルーティング
$router = new Router();
$router->routing();



?>

