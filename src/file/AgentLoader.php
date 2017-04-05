<?php

namespace adf\file;

use adf\Config;

class AgentLoader {
	public static function getAgents() {
		
		// ファイルの取得
		$agentDir = Config::$ROUTER_PATH . Config::AGENTS_DIR_NAME;
		$files = scandir ( $agentDir );
		$files = array_filter ( $files, function ($file) { // 注(1)
			return ! in_array ( $file, array (
					'.',
					'..' 
			) );
		} );
		
		// 実際にAgentのデータを詰める
		$agents = [ ];
		
		foreach ( $files as $file ) {
			
			if (is_dir ( $agentDir . "/" . $file )) {
				// Jsonデータを取得
				$json = file_get_contents ( $agentDir . "/" . $file . "/" . Config::AGENT_META_JSON );
				$json = mb_convert_encoding ( $json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' );
				$obj = json_decode ( $json, true );
				$obj ['upload_date'] = date ( "Y年m月d日 H時i分s秒", $obj ['upload_date'] );
				
				$agents [] = $obj;
			}
		}
		
		return $agents;
	}
	public static function getAgent($uuid) {
		$agentDir = Config::$ROUTER_PATH . Config::AGENTS_DIR_NAME;
		$agentFile = $agentDir . "/" . $uuid;
		
		$files = scandir ( $agentDir );
		$files = array_filter ( $files, function ($file) { // 注(1)
			return ! in_array ( $file, array (
					'.',
					'..' 
			) );
		} );
		
		foreach ( $files as $file ) {
			
			if(preg_match("/".$uuid."$/",$file)){
				
				$json = file_get_contents ( $agentDir . "/" . $file . "/" . Config::AGENT_META_JSON );
				$json = mb_convert_encoding ( $json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' );
				$obj = json_decode ( $json, true );
				$obj ['upload_date'] = date ( "Y年m月d日 H時i分s秒", $obj ['upload_date'] );
				
				return $obj;
				
			}
			
		}
		
		return null;
	}
}

