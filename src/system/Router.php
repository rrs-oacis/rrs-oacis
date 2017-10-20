<?php

namespace rrsoacis\system;

use rrsoacis\manager\AppManager;
use rrsoacis\manager\AccessManager;
use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\Exception\HttpRouteNotFoundException;
use Phroute\Phroute\Exception\HttpMethodNotAllowedException;
use rrsoacis\component\dashboard\DashboardController;
use rrsoacis\exception\AgentNotFoundException;

class Router
{
	function routing()
	{
		$router = new RouteCollector();

		if (AccessManager::restricted())
		{
            // Index (dashboard)
            $router->controller('/', 'rrsoacis\\component\\dashboard\\RestrictedDashboardController');
            $router->controller('/index.php', 'rrsoacis\\component\\dashboard\\RestrictedDashboardController');
            $router->controller('/settings-login', 'rrsoacis\\component\\setting\\general\\SettingsLoginController');
            $router->controller('/settings-login_auth', 'rrsoacis\\component\\setting\\general\\SettingsLoginAuthController');
		}
		else
        {
            // Index (dashboard)
            $router->controller('/', 'rrsoacis\\component\\dashboard\\DashboardController');
            $router->controller('/index.php', 'rrsoacis\\component\\dashboard\\DashboardController');

            // Settings
            $router->controller('/settings', 'rrsoacis\\component\\setting\\SettingsController');
            $router->controller('/settings-apps', 'rrsoacis\\component\\setting\\app\\SettingsAppsListController');
            $router->controller('/settings-app', 'rrsoacis\\component\\setting\\app\\SettingsAppController');
            $router->controller('/settings-app_enable', 'rrsoacis\\component\\setting\\app\\SettingsAppEnableController');
            $router->controller('/settings-clusters', 'rrsoacis\\component\\setting\\cluster\\SettingsClustersListController');
            $router->controller('/settings-clusters_widget', 'rrsoacis\\component\\setting\\cluster\\SettingsClustersListWidgetController');
            $router->controller('/settings-clusters_collector', 'rrsoacis\\component\\setting\\cluster\\SettingsClustersCollectorContentsController');
            $router->controller('/settings-cluster', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterController');
            $router->controller('/settings-cluster_contents', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterContentsController');
            $router->controller('/settings-cluster_statusupdate', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterUpdateStatusController');
            $router->controller('/settings-cluster_update', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterUpdateController');
            $router->controller('/settings-cluster_remove', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterRemoveController');
            $router->controller('/settings-general', 'rrsoacis\\component\\setting\\general\\SettingsGeneralController');
            $router->controller('/settings-version_content', 'rrsoacis\\component\\setting\\general\\SettingsVersionContentController');
            $router->controller('/settings-version_update', 'rrsoacis\\component\\setting\\general\\SettingsVersionUpdateController');
            $router->controller('/settings-restrict_set', 'rrsoacis\\component\\setting\\general\\SettingsRestrictAccessSetController');
            $router->controller('/settings-restrict_set_unrestrected', 'rrsoacis\\component\\setting\\general\\SettingsRestrictAccessHostsController');
            $router->controller('/settings-restrict_set_password', 'rrsoacis\\component\\setting\\general\\SettingsRestrictAccessPasswordController');
            $router->controller('/settings-license', 'rrsoacis\\component\\setting\\license\\SettingsLicenseController');


            // Maps
            $router->controller('/maps', 'rrsoacis\\component\\map\\MapsListController');
            $router->controller('/maps_get', 'rrsoacis\\component\\map\\MapListGetController');
            $router->controller('/map_upload', 'rrsoacis\\component\\map\\MapFileUploadController');

            // Agents
            $router->controller('/agents', 'rrsoacis\\component\\agent\\AgentListController');
            $router->controller('/agents_get', 'rrsoacis\\component\\agent\\AgentListGetController');
            $router->controller('/agents_archived_get', 'rrsoacis\\component\\agent\\AgentArchivedListGetController');

            $router->controller('/agent', 'rrsoacis\\component\\agent\\AgentController');
            $router->controller('/agent_upload', 'rrsoacis\\component\\agent\\AgentFileUploadController');
            $router->controller('/agent_archived_change', 'rrsoacis\\component\\agent\\AgentArchivedController');

            //$router->controller('/result', 'rrsoacis\\component\\ResultController');
            /*
            $router->controller('/result_final', 'rrsoacis\\component\\ResultFinalController');
            $router->controller('/result_json', 'rrsoacis\\component\\ResultJsonController');
            $router->controller('/result_simple', 'rrsoacis\\component\\ResultSimpleController');
            $router->controller('/result_map', 'rrsoacis\\component\\ResultMapController');
            $router->controller('/result_download', 'rrsoacis\\component\\ResultDownloadController');
            */


            // auto-register connected apps
            foreach (AppManager::getConnectedApps() as $app) {

                $packageName = preg_split('/[\\/]/',$app['package'])[1];

                $router->controller('/' . $packageName, $app['main_controller']);
                foreach ($app['sub_controller'] as $controller) {
                    if(isset($controller[0])&&isset($controller[1])){
                        $router->controller('/' . $packageName . '-' . $controller[0], $controller[1]);
                    }
                }
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
			
			header("HTTP/1.0 400 Bad Request");
			include (Config::$SRC_REAL_URL . 'component/error/400ErrorView.php');
			exit ();
		} catch ( HttpMethodNotAllowedException $e ) {
			
            header("HTTP/1.0 400 Bad Request");
			include (Config::$SRC_REAL_URL . 'component/error/400ErrorView.php');
			exit ();
		} catch (AgentNotFoundException $e){
			
            header("HTTP/1.0 400 Bad Request");
			include (Config::$SRC_REAL_URL . 'component/error/400ErrorView.php');
			exit ();
		}
	}
}

?>
