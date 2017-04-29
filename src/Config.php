<?php

namespace adf;

class Config{
	
	//アプリの名前
	const APP_NAME = "RRS-OACIS";
	
	//アプリの略称
	const APP_MIN_NAME = "RO";
	
	//バージョン
	const APP_VERSION = "0.0.1";
	
	//アップロードされたZpiを保存するDirの名前
	const UPLOAD_DIR_NAME=  "zip_agents";
	
	//エージェントデータを保管するDir 未使用
	const AGENTS_DIR_NAME=  "agents";
	
	//エージェントを管理するDir 絶対パス
	const AGENTS_DIR=  "/home/oacis/adf/rrsenv/AGENT";
	
	//エージェントのデータを管理するメタJson
	const AGENT_META_JSON = "agent_meta.json";
	
	//起点(public/_app.html)からのsrcの位置
	static $SRC_REAL_URL = "./src/";
	
	static $RESOURCE_PATH = "/";
	
	static $TOP_PATH = "/";
	
	static $ROUTER_PATH = "/";
	
}

?>