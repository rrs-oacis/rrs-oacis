<?php
namespace adf\apps\results;

use adf\controller\AbstractController;
use adf\apps\results\model\ResultGeneration;
use adf\apps\results\model\ResultTeam;
use adf\apps\results\model\ResultHelper;
use adf\apps\results\model\esult2016;
use adf\apps\results\model\ResultExcel;
use adf\apps\results\model\ResultDownload;

class ResultDownloadController extends AbstractController{
	
	public function anyIndex($param= null,$preParam= null){
		
		ResultDownload::downloadPage($param,$preParam);
		
		//echo 'aaa';
		
	}
	
}
