<?php

namespace adf;

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;

class Router {
	
	function routing() {
		//ルーティング用ライブラリの読み込み
		$router = new RouteCollector ();
		
		$router->any ( '/example', function () {
			return 'This route responds to any method (POST, GET, DELETE etc...) at the URI /example';
		} );
		
		$router->any ( '/', function () {
			// トップページ
			include ('./controller/Home.php');
			return;
		} );
		
		// Print out the value returned from the dispatched function
		try {
			$dispatcher = new Dispatcher ( $router->getData () );
			$response = $dispatcher->dispatch ( $_SERVER ['REQUEST_METHOD'], $_SERVER ['REQUEST_URI'] );
			echo $response;
			exit ();
		} catch ( Phroute\Phroute\Exception\HttpRouteNotFoundException $e ) {
			print '<pre>';
			print_r ( $e );
			print '</pre>';
			exit ();
		} catch ( Phroute\Phroute\Exception\HttpMethodNotAllowedException $e ) {
			print '<pre>';
			print_r ( $e );
			print '</pre>';
			exit ();
		}
	}
}

?>
