<?php 
ini_set ( 'display_errors', 1 );

require '../vendor/autoload.php';


use adf\Router;

//URLを元にルーティング
$router = new Router();
$router->routing();

?>

