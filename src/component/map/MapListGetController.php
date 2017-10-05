<?php
namespace rrsoacis\component\map;

use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\MapManager;

class MapListGetController extends AbstractController
{
  public function get()
  {
    echo json_encode( MapManager::getMaps() );
  }
}