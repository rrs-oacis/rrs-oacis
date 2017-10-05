<?php
namespace rrsoacis\apps\results;

use rrsoacis\component\common\AbstractController;
use rrsoacis\apps\results\model\ResultGeneration;
use rrsoacis\apps\results\model\ResultTeam;
use rrsoacis\apps\results\model\ResultHelper;
use rrsoacis\apps\results\model\esult2016;
use rrsoacis\apps\results\model\ResultExcel;
use rrsoacis\apps\results\model\ResultDownload;

class ResultDownloadController extends AbstractController{
	
	public function anyIndex($param= null,$preParam= null){
		error_reporting(0);
		
		ResultDownload::downloadPage($param,$preParam);
		
		//echo 'aaa';
		
	}
	
}
