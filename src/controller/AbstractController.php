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
			if($param!=null){
				self::getP($param);
			}else{
				self::get();
			}
		}
		
	}
	
	public function getP($param= null){
		throw new HttpMethodNotAllowedException('[AbstractController] getP() : ' .$_SERVER ['REQUEST_URI']);
	}
	
	public function get(){
		throw new HttpMethodNotAllowedException('[AbstractController] get() : ' .$_SERVER ['REQUEST_URI']);
	}
	
	public function post(){
		throw new HttpMethodNotAllowedException('[AbstractController] post() : ' .$_SERVER ['REQUEST_URI']);
	}
	
}

?>