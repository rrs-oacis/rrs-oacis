<?php
namespace adf\controller;

use adf\controller\AbstractController;
use adf\model\ResultGeneration;
use adf\model\ResultTeam;
use adf\model\ResultHelper;
use adf\model\Result2016;
use adf\model\ResultExcel;
use adf\model\ResultDownload;

class ResultDownloadController extends AbstractController{
	
	public function anyIndex($param= null){
		
		ResultDownload::downloadPage($param);
		
		//echo 'aaa';
		
	}
	
}
