<?php

namespace adf\file;

use adf\Config;
use adf\Agent;
use adf\error\AgentNotFoundException;

class AgentLoader {
	
	/**
	 * エージェント一覧をFileから取得する
	 * @return Agent[] json
	 * 
	 * */
	public static function getAgents() {
		
		// ファイルの取得
		$agentDir = Config::$ROUTER_PATH . Config::AGENTS_DIR_NAME;
		
		if (! file_exists ( $agentDir)) {
			mkdir ( $agentDir,0777,true);
		}
		
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
				
				//$agent = new Agent();
				//$agent->setJson($json);
				
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
			
			//File名のUUIDが一致するか　
			if(preg_match("/".$uuid."$/",$file)){
				
				$json = file_get_contents ( $agentDir . "/" . $file . "/" . Config::AGENT_META_JSON );
				$json = mb_convert_encoding ( $json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' );
				$obj = json_decode ( $json, true );
				$obj ['upload_date'] = date ( "Y年m月d日 H時i分s秒", $obj ['upload_date'] );
				
				return $obj;
				
			}
			
		}
		
		throw new AgentNotFoundException('Not Found Agent : UUID = '.$uuid);
		
		return null;
	}
}

