<?php

namespace rrsoacis\system;

/**
 * エージェントのデータクラス
 * 
 * */
class Agent{
	
	private $name;
	private $uuid;
	private $upload_date;
	private $status;

	function __construct() {
		
	}
	
	public function setName($name){
		$this->name = $name;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setUUID($uuid){
		$this->uuid = $uuid;
	}
	
	public function getUUID(){
		return $this->uuid;
	}
	
	public function setUploadDate($upload_date){
		$this->upload_date= $upload_date;
	}
	
	public function getUploadDate(){
		return $this->upload_date;
	}
	
	public function getShowUploadDate(){
		return date ( "Y年m月d日 H時i分s秒", $this->upload_date);
	}
	
	public function setStatus($status){
		$this->status = $status;
	}
	
	public function getStatus(){
		return $this->status;
	}
	
	
	public function getJson()
	{
		
		$metaData = [];
		$metaData['name'] = $this->name;
		$metaData['uuid'] = $this->uuid;
		$metaData['upload_date'] = $this->upload_date;
		$metaData['status'] = $this->status;
		
		return json_encode($metaData);
	}
	
	public function setJson(string $jsonString){
		
		$obj = json_decode ( $jsonString, true );
		$this->name = $obj['name'];
		$this->uuid = $obj['uuid'];
		$this->upload_date = $obj['upload_date'];
		$this->status= $obj['status'];
		
	}
	
}

