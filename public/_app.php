<?php 
//phpinfo();

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
use adf\Localize;

Config::$ROUTER_PATH = $ROUTER_PATH;
Config::$SRC_REAL_URL = $SRC_REAL_URL;

//静的FileへのPath
$resourcePath = dirname($_SERVER["SCRIPT_NAME"]);
if(mb_substr(dirname($_SERVER["SCRIPT_NAME"]), -1)!="/"){
	$resourcePath = $resourcePath . "/";
}

Config::$RESOURCE_PATH =  (empty($_SERVER["HTTPS"]) ? "http://" : "https://"). $_SERVER['HTTP_HOST'].$resourcePath;
Config::$TOP_PATH = Config::$RESOURCE_PATH;

$__postfix = 'png|jpg|jpeg|gif|js|css|html|eot|svg|ttf|woff|woff2|otf';

//$pattern= '/^(.*)(?P<postfix>\.\w*)(\?(.*))*/';

//$url_s = '';

$url = explode('?', $_SERVER["REQUEST_URI"], 2);
//var_dump($url);

//preg_match($pattern, $url[0], $url_s);

//var_dump( $_SERVER["REQUEST_URI"]);

//var_dump($url_s);

//PHPのビルトインウェブサーバー用
//if (preg_match('/\.(?:'.$__postfix.')$/', $_SERVER["REQUEST_URI"])) {
if (preg_match('/\.(?:'.$__postfix.')$/', $url[0])) {
//if(file_exists($_SERVER["REQUEST_FILENAME"])){
	return false;    // リクエストされたリソースをそのままの形式で扱います。
}

Localize::init();

//URLを元にルーティング
$router = new Router();
$router->routing();



?>

<?php 

function _l($s){
	return Localize::getI18N($s);
}

function getOacisURL(){
	//OacisのURL とりあえずポート番号は固定
	return (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["SERVER_NAME"] . ":3000";
}

?>

