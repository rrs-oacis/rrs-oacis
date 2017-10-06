<?php
namespace rrsoacis\manager;

use \PDO;
use rrsoacis\system\Config;

class DatabaseManager
{
    public static function getDatabase($appName)
    {
        echo "<pre>";
        print_r(debug_backtrace());
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