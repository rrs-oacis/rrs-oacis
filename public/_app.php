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

use rrsoacis\system\Router;
use rrsoacis\system\Config;

Config::$ROUTER_PATH = $ROUTER_PATH;
Config::$SRC_REAL_URL = $SRC_REAL_URL;

$resourcePath = dirname($_SERVER["SCRIPT_NAME"]);
if(mb_substr(dirname($_SERVER["SCRIPT_NAME"]), -1)!="/"){
    $resourcePath = $resourcePath . "/";
}

Config::$RESOURCE_PATH =  (empty($_SERVER["HTTPS"]) ? "http://" : "https://"). $_SERVER['HTTP_HOST'].$resourcePath;
Config::$TOP_PATH = Config::$RESOURCE_PATH;

$__suffix = 'php|png|jpg|jpeg|gif|js|css|html|eot|svg|ttf|woff|woff2|otf';

$url = explode('?', $_SERVER["REQUEST_URI"], 2);

if (preg_match('/\.(?:'.$__suffix.')$/', $url[0])) {
    return false;
}

$request = explode('/', $url[0]);

$router = new Router();
$router->routing($request);
?>

