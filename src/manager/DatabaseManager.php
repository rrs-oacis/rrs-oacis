<?php
namespace rrsoacis\manager;

use \PDO;
use rrsoacis\system\Config;

class DatabaseManager
{
    public static function getDatabase()
    {
        $appName = preg_replace('/^.*\/apps\/(.*)\/.*$/', '${1}', debug_backtrace()[0]{"file"});

        if (! file_exists(Config::$ROUTER_PATH."data"))
        {
            mkdir(Config::$ROUTER_PATH."data",0777,true);
        }

        $db = new PDO('sqlite:'.Config::$ROUTER_PATH."data/".$appName.".db");
        $sth = $db->query("select * from sqlite_master where name='system';");
        if (0 >= $sth->rowCount())
        {
            $db->query("CREATE TABLE system(name,value);");
        }

        return $db;
    }

    public static function getSystemDatabase()
    {
        if (! file_exists(Config::$ROUTER_PATH."data"))
        {
            mkdir(Config::$ROUTER_PATH."data",0777,true);
        }

        $db = new PDO('sqlite:'.Config::$ROUTER_PATH."data/main.db");
        $sth = $db->query("select * from sqlite_master where name='system';");
        if (0 >= $sth->rowCount())
        {
            $db->query("CREATE TABLE system(name,value);");
        }

        return $db;
    }
}