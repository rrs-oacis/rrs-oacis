<?php
namespace adf\controller;

use adf\controller\AbstractController;
use adf\file\MapLoader;

class MapListGetController extends AbstractController
{
  public function get()
  {
    echo json_encode( MapLoader::getMaps() );
  }
}