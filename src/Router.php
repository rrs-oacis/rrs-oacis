<?php

namespace adf;

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\Exception\HttpRouteNotFoundException;
use Phroute\Phroute\Exception\HttpMethodNotAllowedException;
use adf\controller\IndexController;
use adf\error\AgentNotFoundException;

class Router {
	function routing() {
		
		// ルーティング用ライブラリの読み込み
		$router = new RouteCollector ();
		
		/*
		$router->any ( '/example', function () {
			return 'This route responds to any method (POST, GET, DELETE etc...) at the URI /example';
		} );
		
		// 静的ファイルのルーティングテスト
		$router->any ( '/test', function () {
			include ('./test.html');
			return;
		} );*/
		
		/*
		 * $router->any ( '/', function () {
		 * // トップページ
		 * // include (Config::SRC_REAL_URL . 'controller/Home.php');
		 * // Controller::dispatch(new IndexController);
		 * IndexController::render ();
		 * return;
		 * } );?
		 */
		
		
		
		//トップページ　
		$router->controller ( '/', 'adf\\controller\\IndexController' );
		$router->controller ( '/index.php', 'adf\\controller\\IndexController' );
		
		$router->controller ( '/setting', 'adf\\controller\\SettingController' );
		
		//Zipアップロード(Post)
		$router->controller('/agent_upload', 'adf\\controller\\FileUploadController');
		
		//エージェントのリスト
		$router->controller('/agents', 'adf\\controller\\AgentListController');
		
		//エージェントのリスト
		$router->controller('/agents_get', 'adf\\controller\\AgentListGetController');
		
		//エージェントの詳細画面
		$router->controller('/agent', 'adf\\controller\\AgentController');
		
		
		/*$router->any ( '/agent/upload', function () {
			// zipを受け取る
			include (Config::SRC_REAL_URL . 'controller/FileUpload.php');
			return;
		} );*/
		
		
		// Print out the value returned from the dispatched function
		try {
			
			$url = $_SERVER ['REQUEST_URI'];
		
			if(dirname($_SERVER["SCRIPT_NAME"])!="/"){
				$url = str_replace(dirname($_SERVER["SCRIPT_NAME"]), '', $url);
			}
			
			$dispatcher = new Dispatcher ( $router->getData () );
			$response = $dispatcher->dispatch ( $_SERVER ['REQUEST_METHOD'], $url);
			echo $response;
			exit ();
		} catch ( HttpRouteNotFoundException $e ) {
			
			header("HTTP/1.0 404 Not Found");
			include (Config::$SRC_REAL_URL . 'view/404ErrorView.php');
			//echo 'bb';
			//print '<pre>';
			//print_r ( $e );
			//print '</pre>';
			exit ();
		} catch ( HttpMethodNotAllowedException $e ) {
			
			header("HTTP/1.0 404 Not Found");
			include (Config::$SRC_REAL_URL . 'view/404ErrorView.php');
			//print '<pre>';
			//print_r ( $e );
			//print '</pre>';
			exit ();
		} catch (AgentNotFoundException $e){
			
			header("HTTP/1.0 404 Not Found");
			include (Config::$SRC_REAL_URL . 'view/404ErrorView.php');
			exit ();
		}
	}
}

?>
