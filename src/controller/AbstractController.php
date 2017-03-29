<?php

namespace adf\controller;

use Phroute\Phroute\Exception\HttpMethodNotAllowedException;

abstract class AbstractController{
	
	public function anyIndex(){
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			self::post();
		}else{
			self::get();
		}
		
	}
	
	public function get(){
		throw new HttpMethodNotAllowedException('get : ' .$_SERVER ['REQUEST_URI']);
	}
	
	public function post(){
		throw new HttpMethodNotAllowedException('post : ' .$_SERVER ['REQUEST_URI']);
	}
	
}

?>