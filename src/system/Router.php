<?php

namespace rrsoacis\system;

use rrsoacis\component\common\AbstractController;
use rrsoacis\exception\HttpRouteNotFoundException;
use rrsoacis\manager\AppManager;
use rrsoacis\manager\AccessManager;

class Router
{
    function registration()
    {
        if (AccessManager::restricted()) {
            // Index (dashboard)
            $this->register('/', 'rrsoacis\\component\\dashboard\\RestrictedDashboardController');
            $this->register('/settings-login', 'rrsoacis\\component\\setting\\general\\SettingsLoginController');
            $this->register('/settings-login_auth', 'rrsoacis\\component\\setting\\general\\SettingsLoginAuthController');
        } else {
            // Index (dashboard)
            $this->register('/', 'rrsoacis\\component\\dashboard\\DashboardController');

            // Settings
            $this->register('/settings', 'rrsoacis\\component\\setting\\SettingsController');
            $this->register('/settings-apps', 'rrsoacis\\component\\setting\\app\\SettingsAppsListController');
            $this->register('/settings-app', 'rrsoacis\\component\\setting\\app\\SettingsAppController');
            $this->register('/settings-app_enable', 'rrsoacis\\component\\setting\\app\\SettingsAppEnableController');
            $this->register('/settings-clusters', 'rrsoacis\\component\\setting\\cluster\\SettingsClustersListController');
            $this->register('/settings-clusters_widget', 'rrsoacis\\component\\setting\\cluster\\SettingsClustersListWidgetController');
            $this->register('/settings-clusters_collector', 'rrsoacis\\component\\setting\\cluster\\SettingsClustersCollectorContentsController');
            $this->register('/settings-cluster', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterController');
            $this->register('/settings-cluster_contents', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterContentsController');
            $this->register('/settings-cluster_statusupdate', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterUpdateStatusController');
            $this->register('/settings-cluster_update', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterUpdateController');
            $this->register('/settings-cluster_remove', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterRemoveController');
            $this->register('/settings-cluster_enable', 'rrsoacis\\component\\setting\\cluster\\SettingsClusterEnableController');
            $this->register('/settings-general', 'rrsoacis\\component\\setting\\general\\SettingsGeneralController');
            $this->register('/settings-version_content', 'rrsoacis\\component\\setting\\general\\SettingsVersionContentController');
            $this->register('/settings-version_update', 'rrsoacis\\component\\setting\\general\\SettingsVersionUpdateController');
            $this->register('/settings-restrict_set', 'rrsoacis\\component\\setting\\general\\SettingsRestrictAccessSetController');
            $this->register('/settings-restrict_set_unrestrected', 'rrsoacis\\component\\setting\\general\\SettingsRestrictAccessHostsController');
            $this->register('/settings-restrict_set_password', 'rrsoacis\\component\\setting\\general\\SettingsRestrictAccessPasswordController');
            $this->register('/settings-license', 'rrsoacis\\component\\setting\\license\\SettingsLicenseController');


            // Maps
            $this->register('/maps', 'rrsoacis\\component\\map\\MapsListController');
            $this->register('/maps_get', 'rrsoacis\\component\\map\\MapListGetController');
            $this->register('/map_upload', 'rrsoacis\\component\\map\\MapFileUploadController');

            // Agents
            $this->register('/agents', 'rrsoacis\\component\\agent\\AgentListController');
            $this->register('/agents_get', 'rrsoacis\\component\\agent\\AgentListGetController');
            $this->register('/agents_archived_get', 'rrsoacis\\component\\agent\\AgentArchivedListGetController');

            $this->register('/agent', 'rrsoacis\\component\\agent\\AgentController');
            $this->register('/agent_upload', 'rrsoacis\\component\\agent\\AgentFileUploadController');
            $this->register('/agent_archived_change', 'rrsoacis\\component\\agent\\AgentArchivedController');

            //$this->register('/result', 'rrsoacis\\component\\ResultController');
            /*
            $this->register('/result_final', 'rrsoacis\\component\\ResultFinalController');
            $this->register('/result_json', 'rrsoacis\\component\\ResultJsonController');
            $this->register('/result_simple', 'rrsoacis\\component\\ResultSimpleController');
            $this->register('/result_map', 'rrsoacis\\component\\ResultMapController');
            $this->register('/result_download', 'rrsoacis\\component\\ResultDownloadController');
            */


            // auto-register connected apps
            foreach (AppManager::getConnectedApps() as $app) {
                $packageName = preg_replace('/^rrs_oacis\/(.*)$/', '${1}', $app['package']);

                $this->register('/'.$packageName, $app['main_controller']);
                foreach ($app['sub_controller'] as $controller) {
                    if (isset($controller[0]) && isset($controller[1])) {
                        $this->register('/'.$packageName . '-' . $controller[0], $controller[1]);
                    }
                }
            }
        }
    }

    public function __construct()
    {
        $this->directory = array();
        $this->registration();
    }

    protected $directory;
    protected function register($key = "", $page = "")
    {
        $this->directory[$key] = $page;
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
                    $searchKey .= $request[$i].'/';
                }
                $searchKey = substr($searchKey, 0, -1);

                $params = array();
                for ($i = $bindSize; $i < count($request); $i++) {
                    $params[] = $request[$i];
                }

                if (array_key_exists($searchKey, $this->directory)) {
                    $class = new $this->directory[$searchKey];
                    if ($class instanceof AbstractController) {
                        call_user_func_array(array($class, "anyIndex"), $params);
                    } else if ($class instanceof AbstractController) {
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
