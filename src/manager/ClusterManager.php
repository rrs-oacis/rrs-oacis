<?php

namespace rrsoacis\manager;

use \MongoClient;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use \PDO;
use rrsoacis\system\Config;
use rrsoacis\system\Agent;
use rrsoacis\exception\AgentNotFoundException;

class ClusterManager
{
    const MAIN_HOST_GROUP = 'RRS-OACIS';

	/**
	 *
	 * */
	public static function getClusters()
    {
        $db = self::connectDB();
        $sth = $db->query("select * from cluster;");
        $clusters = [];
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $clusters [] = $row;
        }

		return $clusters;
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

    public static function getMainHostGroup()
    {
        $db = self::connectDB();
        $sth = $db->query("select value from system where name='mainHostGroupName';");
        $name = $sth->fetch(PDO::FETCH_ASSOC)['value'];
        return $name;
    }

    /**
     *
     * */
    public static function updateCluster($name, $a_host, $f_host, $p_host, $s_host)
    {
        $workspaceDir = Config::$ROUTER_PATH.Config::WORKSPACE_DIR_NAME;
        if (! file_exists ( $workspaceDir )) { mkdir( $workspaceDir ); }

        $db = self::connectDB();
        $sth = $db->prepare("select count(*) as num from cluster where name=:name;");
        $sth->bindValue(':name', $name, PDO::PARAM_STR);
        $sth->execute();
        $num = $sth->fetch(PDO::FETCH_ASSOC)['num'];

        if ($num > 0)
        {
            $sth = $db->prepare("update cluster set a_host=:a_host, f_host=:f_host, p_host=:p_host, s_host=:s_host where name=:name;");
        }
        else
        {
            /* BEGIN : direct OACIS control */
            $oacisdb = self::connectOacisDB();
            $oaciscoll = $oacisdb->selectCollection("hosts");
            $hosts = $oaciscoll->find(array("name" => "localhost"));
            foreach ($hosts as $entry)
            { $base = $entry; }

            $name = new ObjectID();

            $myWorkspaceDir = Config::$ROUTER_PATH.Config::WORKSPACE_DIR_NAME.'/'.$name;
            mkdir($myWorkspaceDir);
            $config = "SERVER_SS=\"".$s_host."\"\nSERVER_C1=\"".$f_host."\"\nSERVER_C2=\"".$p_host."\"\nSERVER_C3=\"".$a_host."\"\n";
            file_put_contents($myWorkspaceDir.'/config.cfg', $config);
            system("chown -R oacis:oacis ".Config::$ROUTER_PATH.Config::WORKSPACE_DIR_NAME);
            $myWorkspaceDir = '~/rrs-oacis/'.Config::WORKSPACE_DIR_NAME.'/'.$name;

            $base['_id'] = $name;
            $base['name'] = 'RO_'.$name;
            $base['work_base_dir'] = $myWorkspaceDir;
            $base['mounted_work_base_dir'] = ''; //$myWorkspaceDir;
            $oaciscoll->insertOne($base);
            /* END : direct OACIS control */

            $sth = $db->prepare("insert into cluster(name, a_host, f_host, p_host, s_host) values(:name, :a_host, :f_host, :p_host, :s_host);");
        }
        $sth->bindValue(':name', $name, PDO::PARAM_STR);
        $sth->bindValue(':a_host', $a_host, PDO::PARAM_STR);
        $sth->bindValue(':f_host', $f_host, PDO::PARAM_STR);
        $sth->bindValue(':p_host', $p_host, PDO::PARAM_STR);
        $sth->bindValue(':s_host', $s_host, PDO::PARAM_STR);
        $sth->execute();

        self::updateHostGroup();
    }

    /**
     *
     * */
    private static function updateHostGroup()
    {
        $oacisdb = self::connectOacisDB();

        $db = self::connectDB();
        $sth = $db->query("select count(*) as num from cluster;");
        $num = $sth->fetch(PDO::FETCH_ASSOC)['num'];

        // destroy HostGroup in all cases
        $sth = $db->query("select value from system where name='mainHostGroupName';");
        $name = $sth->fetch(PDO::FETCH_ASSOC)['value'];
        if ($name !== '') // if already exist HostGroup
        {
            /* BEGIN : direct OACIS control */
            // destroy HostGroup on mongo
            $oaciscoll = $oacisdb->selectCollection("host_groups");
            $oaciscoll->deleteMany(array("name" => self::MAIN_HOST_GROUP));
            /* END : direct OACIS control */

            $db->query("update system set value='' where name='mainHostGroupName';");
        }

        if ($num > 0) // if exist cluster
        { // setup HostGroup
            $name = new ObjectID();

            /* BEGIN : direct OACIS control */
            // setup HostGroup on mongo
            // e.g. {
            //"_id" : ObjectId("593697bae011d900edec2f3c"),
            //"name" : "RRS-OACIS",
            //"host_ids" : [ ObjectId("593691f518d168898099a4ba"),
            //ObjectId("593696c5e011d9000a7f1674"),
            //ObjectId("59369705e011d9000a7f1675") ],
            //"updated_at" : ISODate("2017-06-06T11:53:30.082Z"),
            //"created_at" : ISODate("2017-06-06T11:53:30.082Z")
            //}

            $hostGroup["_id"] = $name;
            $hostGroup["name"] = self::MAIN_HOST_GROUP;
            $sth = $db->query("select name from cluster;");
            $hostGroup["host_ids"] = [];
            $oaciscoll = $oacisdb->selectCollection("hosts");
            while($row = $sth->fetch(PDO::FETCH_ASSOC))
            {
                $hostid = new ObjectID($row['name']);
                $hostGroup["host_ids"][] = $hostid;
                $oaciscoll->updateOne(array("_id" => $hostid), array('$set' => array("host_group_ids" => array($name))));
            }
            $hostGroup["updated_at"] = new UTCDateTime();
            $hostGroup["created_at"] = new UTCDateTime();
            $oaciscoll = $oacisdb->selectCollection("host_groups");
            $oaciscoll->insertOne($hostGroup);
            /* END : direct OACIS control */

            $sth = $db->prepare("update system set value=:value where name='mainHostGroupName';");
            $sth->bindValue(':value', $name, PDO::PARAM_STR);
            $sth->execute();
        }
    }

    /**
     *
     * */
    private static function connectDB()
    {
        $db = DatabaseManager::getSystemDatabase();
        $connectedAppVersion = 0;
        $sth = $db->query("select value from system where name='clusterVersion';");
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $connectedAppVersion = $row['value'];
        }

        switch ($connectedAppVersion)
        {
            case 0:
                $db->query("insert into system(name,value) values('clusterVersion', 1);");
                $db->query("insert into system(name,value) values('mainHostGroupName', '');");
                $db->query("create table cluster(name, a_host, f_host, p_host);");
            case 1:
                $db->query("alter table cluster add s_host;");
                $version = 2;

                $sth = $db->prepare("update system set value=:value where name='clusterVersion';");
                $sth->bindValue(':value', $version, PDO::PARAM_INT);
                $sth->execute();
            default:
        }

        return $db;
    }

    /**
     *
     * */
    private static function connectOacisDB()
    {
        $client = new \MongoDB\Client("mongodb://localhost:27017");
        $db = $client->oacis_development;

        return $db;
    }
}

