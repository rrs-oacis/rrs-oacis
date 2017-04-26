<?php

namespace adf;
use adf\locale\en;

class Localize{
	
	public static function init(){
		
		//$t = new Translator();
		//$t->register();
		
	}
	
	public static function getI18N($s){
		
		$lo = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		
		//どうにかする
		$lc = 'adf\\locale\\'.$lo;
		if(class_exists($lc)){
		
			if(isset($lc::$l[$s])){
				return $lc::$l[$s];
			}else{
				
				if(isset(en::$l[$s])){
					return en::$l[$s];
				}
				
			}
			
		}
		
		return $s;
	}
	
}