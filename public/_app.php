<?php 
ini_set ( 'display_errors', 1 );
date_default_timezone_set('asia/tokyo');

$ROUTER_PATH = "";
$SRC_REAL_URL = "";

if (php_sapi_name() == 'cli-server') {
	$ROUTER_PATH= "./";
	$SRC_REAL_URL= "./src/";
}else{
	$ROUTER_PATH= "../";
	$SRC_REAL_URL= "../src/";
}

require $ROUTER_PATH.'vendor/autoload.php';

use adf\Router;
use adf\Config;

Config::$ROUTER_PATH = $ROUTER_PATH;
Config::$SRC_REAL_URL = $SRC_REAL_URL;

//静的FileへのPath
$resourcePath = dirname($_SERVER["SCRIPT_NAME"]);
if(mb_substr(dirname($_SERVER["SCRIPT_NAME"]), -1)!="/"){
	$resourcePath = $resourcePath . "/";
}

Config::$RESOURCE_PATH = $_SERVER['HTTP_HOST'].$resourcePath;

//PHPのビルトインウェブサーバー用
if (preg_match('/\.(?:png|jpg|jpeg|gif|js|css|html)$/', $_SERVER["REQUEST_URI"])) {
	return false;    // リクエストされたリソースをそのままの形式で扱います。
}

//URLを元にルーティング
$router = new Router();
$router->routing();



?>

