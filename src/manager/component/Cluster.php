<?php

namespace rrsoacis\manager\component;


class Cluster
{
	const STATUS_DISABLED = 3;

	public $name;
	public $a_host;
	public $f_host;
	public $p_host;
	public $s_host;
	public $check_status;
	public $archiver;

	public function __construct($rawMap)
	{
		$this->name = $rawMap["name"];
		$this->a_host = $rawMap["a_host"];
		$this->f_host = $rawMap["f_host"];
		$this->p_host = $rawMap["p_host"];
		$this->s_host = $rawMap["s_host"];
		$this->check_status = $rawMap["check_status"];
		$this->archiver = $rawMap["archiver"];
	}

	public static function arrayFactory($rawMapArray)
	{
		$array = array();
		foreach ($rawMapArray as $agent) {
			$array[] = new App($agent);
		}
		return $array;
	}
}
