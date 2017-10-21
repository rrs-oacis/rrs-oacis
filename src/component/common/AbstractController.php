<?php

namespace rrsoacis\component\common;

abstract class AbstractController{
	
	public $resource_uri = "./";
	
	public function anyIndex(){

		$resource_uri = $this->resource_uri;

		if($_SERVER["REQUEST_METHOD"] == "POST"){
			$this->post();
		}else{
            $this->get();
		}
		
	}
	
	public function get(){
		throw new HttpMethodNotAllowedException('[AbstractController] get() : ' .$_SERVER ['REQUEST_URI']);
	}
	
	public function post(){
		throw new HttpMethodNotAllowedException('[AbstractController] post() : ' .$_SERVER ['REQUEST_URI']);
	}
	
}

?>