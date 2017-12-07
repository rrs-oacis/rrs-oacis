<?php

namespace rrsoacis\component\map;

use rrsoacis\system\Config;
use rrsoacis\component\common\AbstractController;

class MapImageController extends AbstractController
{

	public function anyIndex($param= null)
	{
		self::get($param);
	}

	public function get($name = null)
	{
		header('Content-Type: image/png');
		echo file_get_contents(Config::$SRC_REAL_URL."../" . Config::MAPS_DIR_NAME."/".$name."/preview.png");
	}

}
