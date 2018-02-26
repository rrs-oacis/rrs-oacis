<?php
namespace rrsoacis\apps\rrs_oacis\results;

use rrsoacis\component\common\AbstractController;
use rrsoacis\apps\rrs_oacis\results\model\ResultGeneration;
use rrsoacis\apps\rrs_oacis\results\model\ResultTeam;
use rrsoacis\apps\rrs_oacis\results\model\ResultHelper;
use rrsoacis\apps\rrs_oacis\results\model\ResultDownload;

class ResultDownloadController extends AbstractController{

	public function anyIndex($param= null,$preParam= null){
		error_reporting(0);

		ResultDownload::downloadPage($param,$preParam);

		//echo 'aaa';

	}

}
