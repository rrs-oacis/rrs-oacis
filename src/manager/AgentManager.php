<?php

namespace rrsoacis\manager;

use \PDO;
use rrsoacis\system\Config;
use rrsoacis\system\Agent;
use rrsoacis\exception\AgentNotFoundException;
use ZipArchive;

class AgentManager
{
	/**
	 * エージェント一覧をFileから取得する
	 * @return Agent[] json
	 * 
	 * */
	public static function getAgents()
    {
        $db = self::connectDB();
        $sth = $db->query("select * from agent where archived = 0;");
        $agents = [];
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
                $agents[] = $row;

        }

		return $agents;
	}

    public static function getArchivedAgents()
    {
        $db = self::connectDB();
        $sth = $db->query("select * from agent where archived = 1;");
        $agents = [];
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {

                $agents[] = $row;


        }

        return $agents;
    }

    public static function getAgent($name)
    {
        $db = self::connectDB();
        $sth = $db->prepare("select * from agent where name=:name;");
        $sth->bindValue(':name', $name, PDO::PARAM_STR);
        $sth->execute();
        $agent = [];
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $agent = $row;
        }

        return $agent;
    }

    public static function getAgentByAlias($alias)
    {
        $db = self::connectDB();
        $sth = $db->prepare("select * from agent where archived = 0 and alias=:alias;");
        $sth->bindValue(':alias', $alias, PDO::PARAM_STR);
        $sth->execute();
        $agent = [];
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $agent = $row;
        }

        return $agent;
    }

    public static function setArchived($name,$archived){

        $db = self::connectDB();

        if($archived==0){

            $agent = self::getAgent($name);

            $sth = $db->prepare("update agent set archived=1 where alias=:alias;");
            $sth->bindValue(':alias', $agent['alias'], PDO::PARAM_STR);
            $sth->execute();
        }

        $sthU = $db->prepare("update agent set archived=:archived where name=:name;");
        $sthU->bindValue(':archived', $archived, PDO::PARAM_INT);
        $sthU->bindValue(':name', $name, PDO::PARAM_STR);
        $sthU->execute();

    }

    /**
     *
     * */
    public static function addAgent($name, $alias)
    {
        $db = self::connectDB();
        $sth = $db->prepare("update agent set archived=1 where alias=:alias;");
        $sth->bindValue(':alias', $alias, PDO::PARAM_STR);
        $sth->execute();
        $sth = $db->prepare("insert into agent(name, alias) values(:name, :alias);");
        $sth->bindValue(':name', $name, PDO::PARAM_STR);
        $sth->bindValue(':alias', $alias, PDO::PARAM_STR);
        $sth->execute();
    }

    /**
     *
     * */
    private static function connectDB()
    {
        $db = DatabaseManager::getSystemDatabase();
        $connectedAppVersion = 0;
        $sth = $db->query("select value from system where name='agentVersion';");
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $connectedAppVersion = $row['value'];
        }

        switch ($connectedAppVersion)
        {
            case 0:
                $db->query("insert into system(name,value) values('agentVersion', 1);");
                $db->query("create table agent(name,alias,archived default 0,timestamp default (DATETIME('now','localtime')));");
                $version = 1;

                $sth = $db->prepare("update system set value=:value where name='agentVersion';");
                $sth->bindValue(':value', $version, PDO::PARAM_INT);
                $sth->execute();
            default:
        }

        return $db;
    }


    //------------------
    //Zip function
    //------------------
    public static function all_zip($agentID )
    {

        $dir_path = Config::$ROUTER_PATH.Config::AGENTS_DIR_NAME . "/" . $agentID;

        $zipFileName = 'agent_'.$agentID.'.zip';

        $zipTmpDir = '/tmp/';

        if(!file_exists($zipTmpDir)){

            if(mkdir($zipTmpDir,'0777', TRUE)){

            }

        }

        $zip = new ZipArchive();
        if( $zip->open( $zipTmpDir.$zipFileName, ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE ) === true ){
            self::add_zip( $zip, $dir_path, "" );
            $zip->close();
        }
        else{
            throw new Exception('It does not make a zip file');
        }

        // stream out put
        header('Content-Type: application/zip; name="' . $zipFileName . '"');
        header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
        header('Content-Length: '.filesize($zipTmpDir.$zipFileName));
        echo file_get_contents($zipTmpDir.$zipFileName);

        // delete tmp
        unlink($zipTmpDir.$zipFileName);

    }

    private static function add_zip( $zip, $dir_path, $new_dir )
    {
        if( ! is_dir( $new_dir ) ){
            $zip->addEmptyDir( $new_dir );
        }

        foreach( self::get_inner_path_of_directory( $dir_path ) as $file ){
            if( is_dir( $dir_path . "/" . $file ) ){
                self::add_zip( $zip, $dir_path . "/" . $file, $new_dir . "/" . $file );
            }
            else{
                $zip->addFile( $dir_path . "/" . $file, $new_dir . "/" . $file );
            }
        }
    }

    public static function get_inner_path_of_directory( $dir_path )
    {
        $file_array = array();
        if( is_dir( $dir_path ) ){
            if( $dh = opendir( $dir_path ) ){
                while( ( $file = readdir( $dh ) ) !== false ){
                    if( $file == "." || $file == ".." ){
                        continue;
                    }
                    $file_array[] = $file;
                }
                closedir( $dh );
            }
        }
        sort( $file_array );
        return $file_array;
    }
}

