<?php

namespace adf;

class Config{
	const APP_NAME = "RRS-OACIS";
	const APP_SHORT_NAME = "RO";
	const APP_VERSION = "0.0.1";

	//アップロードされたZpiを保存するDirの名前
	const TMP_DIR_NAME=  "tmp";
	
	//アップロードされたMapZpiを保存するDirの名前
	const UPLOAD_MAP_DIR_NAME=  "zip_maps";
	
	const WORKSPACE_DIR_NAME=  "rrsenv/workspace";
    const AGENTS_DIR_NAME=  "rrsenv/AGENT";
	const MAPS_DIR_NAME=  "rrsenv/MAP";

    const MAIN_DATABASE=  "data/main.db";

	//エージェントを管理するDir 絶対パス
	//const AGENTS_DIR=  "/home/oacis/adf/rrsenv/AGENT";
	
	//エージェントのデータを管理するメタJson
	const AGENT_META_JSON = "agent_meta.json";
	
	//マップのデータを管理するメタJson
	const MAP_META_JSON = "map_meta.json";
	
	//起点(public/_app.html)からのsrcの位置
    static $SRC_REAL_URL = "./src/";
    static $APPS_REL_PATH = "./apps/";

	static $RESOURCE_PATH = "/";
	
	static $TOP_PATH = "/";
	
	static $ROUTER_PATH = "/";

    static $PUBLICKEY_PATH = "/home/oacis/.ssh/id_rsa.pub";
    static $OACISCLI_PATH = "/home/oacis/oacis/bin/oacis_cli ";
}

?>