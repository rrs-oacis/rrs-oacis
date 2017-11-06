<?php

namespace rrsoacis\system;

use rrsoacis\component\common\AbstractController;
use rrsoacis\component\common\AbstractPage;
use rrsoacis\exception\HttpMethodNotAllowedException;
use rrsoacis\exception\HttpRouteNotFoundException;
use rrsoacis\manager\AppManager;
use rrsoacis\manager\AccessManager;

class Router
{
	function registration()
	{
		if (AccessManager::restricted()) {
			// Index (dashboard)
			$this->register('/', 'rrsoacis\\component\\dashboard\\RestrictedDashboardPage', 0);
			$this->register('/settings-login', 'rrsoacis\\component\\setting\\general\\SettingsLoginPage');
		} else {
			// Index (dashboard)
			$this->register('/', 'rrsoacis\\component\\dashboard\\DashboardPage', 0);

			// Settings
			$this->register('/settings', 'rrsoacis\\component\\setting\\SettingsPage');
			$this->register('/settings-apps', 'rrsoacis\\component\\setting\\app\\SettingsAppsListController');
			$this->register('/settings-app', 'rrsoacis\\component\\setting\\app\\SettingsAppPage');
			$this->register('/settings-app_enable', 'rrsoacis\\component\\setting\\app\\SettingsAppEnableController');
			$this->register('/settings-app_installer', 'rrsoacis\\component\\setting\\app\\SettingsAppInstallerPage');
			$this->register('/settings-clusters', 'rrsoacis\\component\\setting\\cluster\\SettingsClustersListController');
			$this->register('/settings-clusters_widget', 'rrsoacis\\component\\setting\\cluster\\SettingsClustersListWidgetController');
			$this->register('/settings-clusters_collector', 'rrsoacis\\component\\setting\\cluster\\SettingsClustersCollectorContentsController');
			$this->register('/settings-cluster', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterPage');
			$this->register('/settings-cluster_contents', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterContentsController');
			$this->register('/settings-cluster_statusupdate', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterUpdateStatusController');
			$this->register('/settings-cluster_update', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterUpdateController');
			$this->register('/settings-cluster_remove', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterRemoveController');
			$this->register('/settings-cluster_kill', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterKillController');
			$this->register('/settings-cluster_enable', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterEnableController');
			$this->register('/settings-cluster_livelog', 'rrsoacis\\component\\setting\\cluster\\SettingsLiveLogViewerPage');
			$this->register('/settings-general', 'rrsoacis\\component\\setting\\general\\SettingsGeneralController');
			$this->register('/settings-version_content', 'rrsoacis\\component\\setting\\general\\SettingsVersionContentController');
			$this->register('/settings-version_update', 'rrsoacis\\component\\setting\\general\\SettingsVersionUpdateController');
			$this->register('/settings-restrict_set', 'rrsoacis\\component\\setting\\general\\SettingsRestrictAccessSetController');
			$this->register('/settings-restrict_set_unrestrected', 'rrsoacis\\component\\setting\\general\\SettingsRestrictAccessHostsController');
			$this->register('/settings-restrict_set_password', 'rrsoacis\\component\\setting\\general\\SettingsRestrictAccessPasswordController');
			$this->register('/settings-license', 'rrsoacis\\component\\setting\\license\\SettingsLicensePage');

			// Maps
			$this->register('/maps', 'rrsoacis\\component\\map\\MapsListController');
			$this->register('/maps_get', 'rrsoacis\\component\\map\\MapListGetController');
			$this->register('/maps_archived_get', 'rrsoacis\\component\\map\\MapArchivedListGetController');
            $this->register('/maps_archived', 'rrsoacis\\component\\map\\MapsArchivedListController');

			$this->register('/map', 'rrsoacis\\component\\map\\MapController');
			$this->register('/map_upload', 'rrsoacis\\component\\map\\MapFileUploadController');
            $this->register('/map_download', 'rrsoacis\\component\\map\\MapDownloadController');
			$this->register('/map_archived_change', 'rrsoacis\\component\\map\\MapArchivedController');
			$this->register('/map_gml_get', 'rrsoacis\\component\\map\\MapGMLGetController');

			// Agents
			$this->register('/agents', 'rrsoacis\\component\\agent\\AgentListController');
			$this->register('/agents_get', 'rrsoacis\\component\\agent\\AgentListGetController');
			$this->register('/agents_archived_get', 'rrsoacis\\component\\agent\\AgentArchivedListGetController');
            $this->register('/agents_archived', 'rrsoacis\\component\\agent\\AgentsArchivedListController');

			$this->register('/agent', 'rrsoacis\\component\\agent\\AgentController');
			$this->register('/agent_upload', 'rrsoacis\\component\\agent\\AgentFileUploadController');
            $this->register('/agent_download', 'rrsoacis\\component\\agent\\AgentDownloadController');
			$this->register('/agent_archived_change', 'rrsoacis\\component\\agent\\AgentArchivedController');

			// auto-register connected apps
			foreach (AppManager::getConnectedApps() as $app) {
				if ($app['packages_user'] === "rrs-oacis") {
					$this->register('/' . $app['packages_name'], $app['main_controller']);
					foreach ($app['sub_controller'] as $controller) {
						if (isset($controller[0]) && isset($controller[1])) {
							$this->register('/' . $app['packages_name'] . '-' . $controller[0], $controller[1]);
						}
					}
				}

				$this->register('/app/' . $app['package'], $app['main_controller']);
				foreach ($app['sub_controller'] as $controller) {
					if (isset($controller[0]) && isset($controller[1])) {
						$this->register('/app/' . $app['package'] . '-' . $controller[0], $controller[1]);
					}
				}
			}
		}
	}

	public function __construct()
	{
		$this->pageMap = array();
		$this->registration();
	}

	protected $pageMap;
	protected $paramLimitMap;

	protected function register($key = "", $page = "", $paramLimit = -1)
	{
		$this->pageMap[$key] = $page;
		$this->paramLimitMap[$key] = $paramLimit;
	}

	public function routing($request)
	{
		if (count($request) <= 0) {
			$request[0] = "";
		}

		try {
			for ($bindSize = count($request); $bindSize > 0; $bindSize--) {
				$searchKey = "/";
				for ($i = 1; $i < $bindSize; $i++) {
					$searchKey .= $request[$i] . '/';
				}
				$searchKey = substr($searchKey, 0, -1);
				$searchKey = preg_replace('/^\/*/', '/', $searchKey);

				$params = array();
				for ($i = $bindSize; $i < count($request); $i++) {
					$params[] = $request[$i];
				}

				if (array_key_exists($searchKey, $this->pageMap)) {
					$paramLimit = $this->paramLimitMap[$searchKey];
					if ($paramLimit != -1 && $paramLimit < count($params)) {
						continue;
					}

					$class = new $this->pageMap[$searchKey];
					if ($class instanceof AbstractController) {
						echo call_user_func_array(array($class, "anyIndex"), $params);
					} else if ($class instanceof AbstractPage) {
						call_user_func(array($class, "controller"), $params);
					} else {
						throw new HttpRouteNotFoundException('[Router] routing() : ' . $searchKey);
					}
					exit();
				}
			}
			throw new HttpRouteNotFoundException('[Router] routing() : ' . $_SERVER ['REQUEST_URI']);
		} catch (HttpRouteNotFoundException $e) {
			header("HTTP/1.0 400 Bad Request");
			include(Config::$SRC_REAL_URL . 'component/error/400ErrorView.php');
			exit();
		} catch (HttpMethodNotAllowedException $e) {
			header("HTTP/1.0 400 Bad Request");
			include(Config::$SRC_REAL_URL . 'component/error/400ErrorView.php');
			exit();
		}
	}
}

?>
