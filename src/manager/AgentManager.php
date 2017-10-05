<?php

namespace rrsoacis\manager;

use \PDO;
use rrsoacis\system\Config;
use rrsoacis\system\Agent;
use rrsoacis\exception\AgentNotFoundException;

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
        $db = new PDO('sqlite:'.Config::$ROUTER_PATH.Config::MAIN_DATABASE);
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
}

