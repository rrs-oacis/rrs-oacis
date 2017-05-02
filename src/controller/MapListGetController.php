<?php
namespace adf\controller;

use adf\controller\AbstractController;
use adf\file\MapLoader;

class MapListGetController extends AbstractController{
  
  public function get(){
    
    //エージェントのリストを取得
    $maps = MapLoader::getMaps();
    
    echo json_encode($maps);
    
    //include (Config::$SRC_REAL_URL . 'view/AgentListView.php');
  }
  
}