<?php
/**
 * Created by PhpStorm.
 * User: k14041kk
 * Date: 2017/11/09
 * Time: 11:10
 */

namespace rrsoacis\component\map;

use rrsoacis\component\common\AbstractController;
use rrsoacis\apps\rrs_oacis\run\RunManager;
use PDO;

class MapImageController extends AbstractController
{

	public function anyIndex($param= null){
		self::get($param);
	}

	public function get($param = null){

		//echo file_get_contents(Config::$SRC_REAL_URL.'../rrsenv/MAP/'.$param.'/map/map.gml');

		echo self::getMapImageFrom($param);

	}

	public static function getMapImageFrom($map)
	{

		$db = RunManager::getDB();
		$sth = $db->prepare("select * from run where map=:map and status='finished';");
		$sth->bindValue(':map', $map, PDO::PARAM_STR);
		$sth->execute();
		$map = [];
		while($row = $sth->fetch(PDO::FETCH_ASSOC))
		{

			$url = "localhost:3000/Result_development/".$row["simulation"]."/".$row["paramId"]."/".$row["runId"]."/img_log/snapshot-init.png";
			$runImage = @file_get_contents($url);

			return $runImage;

			$map[] = $row;

		}

		return $map;

	}

}
