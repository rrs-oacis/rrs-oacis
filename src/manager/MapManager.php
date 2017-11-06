<?php

namespace rrsoacis\manager;

use \PDO;
use rrsoacis\system\Config;
use rrsoacis\system\Agent;
use rrsoacis\exception\AgentNotFoundException;
use ZipArchive;

class MapManager
{
  
  /**
   * マップ一覧をFileから取得する
   * @return maps[] json
   *
   * */
    public static function getMaps()
    {
        $db = self::connectDB();
        $sth = $db->query("select * from map where archived = 0;");
        $maps = [];
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $maps[] = $row;
        }

        return $maps;
    }

    public static function getArchivedMaps()
    {
        $db = self::connectDB();
        $sth = $db->query("select * from map where archived = 1;");
        $maps = [];
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $maps[] = $row;
        }

        return $maps;
    }

    public static function getMap($name)
    {
        $db = self::connectDB();
        $sth = $db->prepare("select * from map where name=:name;");
        $sth->bindValue(':name', $name, PDO::PARAM_STR);
        $sth->execute();
        $map = [];
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $map = $row;
        }

        return $map;
    }

    public static function getMapByAlias($alias)
    {
        $db = self::connectDB();
        $sth = $db->prepare("select * from map where archived = 0 and alias=:alias;");
        $sth->bindValue(':alias', $alias, PDO::PARAM_STR);
        $sth->execute();
        $map = [];
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $map = $row;
        }

        return $map;
    }

    public static function setArchived($name,$archived){

        $db = self::connectDB();

        if($archived==0){

            $agent = self::getMap($name);

            $sth = $db->prepare("update map set archived=1 where alias=:alias;");
            $sth->bindValue(':alias', $agent['alias'], PDO::PARAM_STR);
            $sth->execute();
        }

        $sthU = $db->prepare("update map set archived=:archived where name=:name;");
        $sthU->bindValue(':archived', $archived, PDO::PARAM_INT);
        $sthU->bindValue(':name', $name, PDO::PARAM_STR);
        $sthU->execute();

    }

    /**
     *
     * */
    public static function addMap($name, $alias)
    {
        $db = self::connectDB();
        $sth = $db->prepare("update map set archived=1 where alias=:alias;");
        $sth->bindValue(':alias', $alias, PDO::PARAM_STR);
        $sth->execute();
        $sth = $db->prepare("insert into map(name, alias) values(:name, :alias);");
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
        $sth = $db->query("select value from system where name='mapVersion';");
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $connectedAppVersion = $row['value'];
        }

        switch ($connectedAppVersion)
        {
            case 0:
                $db->query("insert into system(name,value) values('mapVersion', 1);");
                $db->query("create table map(name,alias,archived default 0,timestamp default (DATETIME('now','localtime')));");
                $version = 1;

                $sth = $db->prepare("update system set value=:value where name='mapVersion';");
                $sth->bindValue(':value', $version, PDO::PARAM_INT);
                $sth->execute();
            default:
        }

        return $db;
    }


    //------------------
    //Zip function
    //------------------
    public static function all_zip($mapID )
    {

        $dir_path = Config::$ROUTER_PATH.Config::MAPS_DIR_NAME . "/" . $mapID;

        $zipFileName = 'map_'.$mapID.'.zip';

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

