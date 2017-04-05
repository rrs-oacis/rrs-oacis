<?php

namespace adf\controller;

use Phroute\Phroute\Exception\HttpMethodNotAllowedException;

abstract class AbstractController{
	
	public $resource_uri = "./";
	
	public function anyIndex($param= null){
		
		$resource_uri = self::$resource_uri;
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			self::post();
		}else{
			self::get($param);
		}
		
	}
	
	public function get($param= null){
		throw new HttpMethodNotAllowedException('get : ' .$_SERVER ['REQUEST_URI']);
	}
	
	public function post(){
		throw new HttpMethodNotAllowedException('post : ' .$_SERVER ['REQUEST_URI']);
	}
	
}

?>