<?php
namespace adf\apps\results\model;

use JsonSerializable;

/**
 * Team's results managing 
 * */
class ResultTeam implements JsonSerializable{
	
	private $teamName;
	
	private $maps;
	
	private $oldDay;
	
	private $rank;
	
	private $logLink;
	
	private $color;
	
	private $presentationPoint = 0;
	
	private $maplog;
	
	private $mapScores;
	
	private $mapInitScores;
	
	private $mapStep;
	
	//HTML Download 
	private $download = false;
	
	public function __construct(String $teamName, $download=false)
	{
		
		$this->teamName = $teamName;
		$this->maps = [];
		$this->mapScores = [];
		$this->mapInitScores = [];
		$this->mapStep = [];
		
		$this->download =$download;


		$this->oldDay = null;
		
	}
	
	public function getMaps(){
		return $this->maps;
	}
	
	public function addMapResult($mapName, $score, $points){
		
		$data = [];
		$data['score'] = floatval($score);
		$data['points'] = $points;
		
		$this->maps[$mapName] = $data;
		
	}
	
	public function setPoint($mapName, $points){
		$this->maps[$mapName]['points'] = $points;
	}
	
	public function getScore($mapName){
		return $this->maps[$mapName];
	}
	
	public function addPresentation($points){
		$this->presentationPoint= $points;
	}
	
	public function getTotalScore(){
	
		$result = [];
		$result['score'] = 0;
		$result['points'] = 0;
		
		foreach($this->maps as $name => $value){
			
			$result['score'] += $value['score'];
			$result['points'] += $value['points'];
			
		}
		
		/*
		if($oldDay!=null){
			$result['score'] += $oldDay['score'];
			$result['points'] += $oldDay['points'];
		}*/
		
		if($this->presentationPoint>0){
			$result['points'] += $this->presentationPoint;
		}
		
		return $result;
		
	}
	
	public function getTeamName(){
		return $this->teamName;
	}
	
	public function setRank($rank){
		$this->rank = $rank;
	}
	
	public function getRank(){
		return $this->rank;
	}
	
	public function setLogLink($link){
		$this->logLink = $link;
	}
	
	public function getLogLink(){
		return $this->logLink;
	}
	
	
	/***
	 * Set Background Color
	 * @param int $color 0, 1, 2, 3 : white, blue, silver, gold
	 */
	public function setColorType($color){
		$this->color = $color;
	}
	
	public function getColorType(){
		return $this->color;
	}
	
	public function addMapLogURI($mapName,$uri){
		
		$this->maps[$mapName]['maplog'] = $uri;
		
	}
	
	public function getMapLogURI($mapName){
		return $this->maps[$mapName]['maplog'];
	}
	
	public function addMapScores($mapName,$scores){
		$this->mapScores[$mapName] = $scores;
	}
	
	public function addMapInitScores($mapName,$score){
		$this->mapInitScores[$mapName] = $score;
	}
	
	public function getMapScores($mapName){
		return $this->mapScores[$mapName];
	}
	
	public function getMapInitScores($mapName){
		return $this->mapInitScores[$mapName];
	}
	
	public function addMapStep($mapName,$step){
		$this->mapStep[$mapName] = intval($step) - 2;
	}
	
	public function getMapStep($mapName){
		return $this->mapStep[$mapName];
	}
	
	public function isDownload(){
		return $this->download;
	}
	
	public function jsonSerialize()
	{
		return get_object_vars($this);
	}
	
}

