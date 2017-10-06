<?php

namespace rrsoacis\manager;

use \PDO;
use rrsoacis\system\Config;
use rrsoacis\system\Agent;
use rrsoacis\exception\AgentNotFoundException;

class AccessManager
{
    public static function setUnrestrictedHost($hosts)
    {
        $hosts = str_replace(" ", "", $hosts);
        $hosts = trim($hosts, ",");
        $db = self::connectDB();
        $sth = $db->prepare("update system set value=:value where name='accessUnrestictedHosts';");
        $sth->bindValue(':value', $hosts, PDO::PARAM_STR);
        $sth->execute();
    }

    public static function restricted()
    {
        if (self::filterEnabled())
        {
            return !(in_array(getenv("REMOTE_ADDR"), AccessManager::getUnrestrictedHosts()));
        }
        return false;
    }

    public static function getUnrestrictedHostsText()
    {
        $db = self::connectDB();
        $value = "";
        $sth = $db->query("select value from system where name='accessUnrestictedHosts';");
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $value = $row['value'];
        }
        return $value;
    }

    public static function getUnrestrictedHosts()
    {
        $hosts = explode(",", self::getUnrestrictedHostsText());
        return $hosts;
    }

    public static function filterEnabled()
    {
        $db = self::connectDB();
        $value = 0;
        $sth = $db->query("select value from system where name='accessFilter';");
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $value = $row['value'];
        }
        return $value;
    }

    public static function enableFilter()
    {
        $db = self::connectDB();
        $db->query("update system set value=1 where name='accessFilter';");
    }

    public static function disableFilter()
    {
        $db = self::connectDB();
        $db->query("update system set value=0 where name='accessFilter';");
    }

    /**
     *
     * */
    private static function connectDB()
	{
        $db = DatabaseManager::getSystemDatabase();
        $accessVersion = 0;
        $sth = $db->query("select value from system where name='accessVersion';");
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $accessVersion = $row['value'];
        }

        switch ($accessVersion)
        {
            case 0:
                $db->query("insert into system(name,value) values('accessVersion', 1);");
                $db->query("insert into system(name,value) values('accessFilter', 0);");
            case 1:
                $db->query("insert into system(name,value) values('accessUnrestictedHosts', '127.0.0.1,172.19.0.1');");
                $version = 2;

                $sth = $db->prepare("update system set value=:value where name='accessVersion';");
                $sth->bindValue(':value', $version, PDO::PARAM_INT);
                $sth->execute();
            default:
        }

        return $db;
	}
}
?>
