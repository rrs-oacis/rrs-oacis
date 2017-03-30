<?php

namespace adf;

class Config{
	
	//アプリの名前
	const APP_NAME = "RRS-OASIC";
	
	//アプリの略称
	const APP_MIN_NAME = "RO";
	
	//バージョン
	const APP_VERSION = "0.0.1";
	
	//アップロードされたZpiを保存するDirの名前
	const UPLOAD_DIR_NAME=  "zip_agents";
	
	//エージェントデータを保管するDir
	const AGENTS_DIR_NAME=  "agents";
	
	//起点(public/index.html)からのsrcの位置
	const SRC_REAL_URL = "../src/";
	
	static $RESOURCE_PATH = "/";
	
}

?>