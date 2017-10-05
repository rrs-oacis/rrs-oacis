<?php

namespace rrsoacis\manager;

use \PDO;
use rrsoacis\system\Config;
use rrsoacis\system\Agent;
use rrsoacis\exception\AgentNotFoundException;

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
        $db = new PDO('sqlite:'.Config::$ROUTER_PATH.Config::MAIN_DATABASE);
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
}

