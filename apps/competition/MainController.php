<?php
namespace adf\apps\competition;

use adf\Config;
use adf\controller\AbstractController;
use adf\file\AppLoader;
use adf\file\MapLoader;

class MainController extends AbstractController
{
    public function get()
    {
        $sessions = SessionManager::getSessions();
        $maps = MapLoader::getMaps();
        include(dirname(__FILE__).'/CompetitionMainView.php');
    }
}
?>
