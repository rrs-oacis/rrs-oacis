<?php
namespace adf\controller;

use adf\Config;
use adf\controller\AbstractController;

class IndexController extends AbstractController{
	
	public function get(){
		include (Config::SRC_REAL_URL . 'view/IndexView.php');
	}
	
}

?>
