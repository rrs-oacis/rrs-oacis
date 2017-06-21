<?php

namespace adf;

use adf\file\AppLoader;
use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\Exception\HttpRouteNotFoundException;
use Phroute\Phroute\Exception\HttpMethodNotAllowedException;
use adf\controller\IndexController;
use adf\error\AgentNotFoundException;

class Router
{
	function routing()
	{
		$router = new RouteCollector();

		// Index (dashboard)
		$router->controller( '/', 'adf\\controller\\IndexController' );
		$router->controller( '/index.php', 'adf\\controller\\IndexController' );

		// Settings
		$router->controller( '/settings', 'adf\\controller\\SettingsController' );
		$router->controller( '/settings-apps', 'adf\\controller\\SettingsAppsListController');
        $router->controller( '/settings-app', 'adf\\controller\\SettingsAppController');
        $router->controller( '/settings-app_enable', 'adf\\controller\\SettingsAppEnableController');
        $router->controller( '/settings-clusters', 'adf\\controller\\SettingsClustersListController');
        $router->controller( '/settings-cluster_update', 'adf\\controller\\SettingsClusterUpdateController');

        // Maps
        $router->controller('/maps', 'adf\\controller\\MapsListController');
        $router->controller('/maps_get', 'adf\\controller\\MapListGetController');
		$router->controller('/map_upload', 'adf\\controller\\MapFileUploadController');
		
		// Agents
		$router->controller('/agents', 'adf\\controller\\AgentListController');
        $router->controller('/agents_get', 'adf\\controller\\AgentListGetController');
        $router->controller('/agent', 'adf\\controller\\AgentController');
        $router->controller('/agent_upload', 'adf\\controller\\AgentFileUploadController');

        //$router->controller('/result', 'adf\\controller\\ResultController');
        $router->controller('/result_final', 'adf\\controller\\ResultFinalController');
        $router->controller('/result_json', 'adf\\controller\\ResultJsonController');
        $router->controller('/result_simple', 'adf\\controller\\ResultSimpleController');
        $router->controller('/result_map', 'adf\\controller\\ResultMapController');
        $router->controller('/result_download', 'adf\\controller\\ResultDownloadController');

		// auto-register connected apps
		foreach ( AppLoader::getConnectedApps() as $app)
		{
            $router->controller('/'.$app['package'], $app['main_controller']);
            foreach ($app['sub_controller'] as $controller)
			{
                $router->controller('/'.$app['package'].'-'.$controller[0], $controller[1]);
			}
		}

		// Print out the value returned from the dispatched function
		try {
			
			$url = $_SERVER['REQUEST_URI'];
		
			if(dirname($_SERVER["SCRIPT_NAME"])!="/"){
				$url = str_replace(dirname($_SERVER["SCRIPT_NAME"]), '', $url);
			}
			
			$dispatcher = new Dispatcher ( $router->getData () );
			$response = $dispatcher->dispatch ( $_SERVER['REQUEST_METHOD'], $url);
			echo $response;
			exit ();
		} catch ( HttpRouteNotFoundException $e ) {
			
			header("HTTP/1.0 404 Not Found");
			include (Config::$SRC_REAL_URL . 'view/404ErrorView.php');
			exit ();
		} catch ( HttpMethodNotAllowedException $e ) {
			
			header("HTTP/1.0 404 Not Found");
			include (Config::$SRC_REAL_URL . 'view/404ErrorView.php');
			exit ();
		} catch (AgentNotFoundException $e){
			
			header("HTTP/1.0 404 Not Found");
			include (Config::$SRC_REAL_URL . 'view/404ErrorView.php');
			exit ();
		}
	}
}

?>
