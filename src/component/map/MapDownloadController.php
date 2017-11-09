<?php
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

		// stream output
		header('Content-Type: application/zip; name="' . $zipFileName . '"');
		header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
		header('Content-Length: ' . (strlen(bin2hex($output))/2));
		echo $output;
	}

}
