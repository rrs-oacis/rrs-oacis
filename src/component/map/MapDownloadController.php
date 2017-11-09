<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/11/06
 * Time: 11:33
 */

namespace rrsoacis\component\map;

use rrsoacis\manager\MapManager;
use rrsoacis\component\common\AbstractController;


class MapDownloadController extends AbstractController
{

	public function anyIndex($param = null)
	{
		$mapName = $param;

		MapManager::updateManifest($mapName);
		$output = MapManager::getZipBinary($mapName);
		$zipFileName = 'map_'.$mapName.'.zip';

		// stream out put
		header('Content-Type: application/zip; name="' . $zipFileName . '"');
		header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
		header('Content-Length: ' . (strlen(bin2hex($output))/2));
		echo $output;
	}

}
