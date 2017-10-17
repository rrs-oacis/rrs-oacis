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
    public static function setPassword($password)
    {
        $db = self::connectDB();
        $sth = $db->prepare("update system set value=:value where name='accessPassword';");
        $sth->bindValue(':value', $password, PDO::PARAM_STR);
        $sth->execute();
    }

    public static function restricted()
    {
        if (self::filterEnabled() && !(in_array(getenv("REMOTE_ADDR"), AccessManager::getUnrestrictedHosts())))
        {
            return true;
        }
        if (self::passwordProtectEnabled())
        {
            $roid = (isset($_COOKIE["roid"]) ? $_COOKIE["roid"] : "");
            $db = self::connectDB();
            $sth = $db->prepare("select id from accessSessionId where value=:value;");
            $sth->bindValue(':value', $roid, PDO::PARAM_STR);
            $sth->execute();
            while($row = $sth->fetch(PDO::FETCH_ASSOC))
            {
                return false;
            }
            return true;
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

    public static function getPassword()
    {
        $db = self::connectDB();
        $value = "";
        $sth = $db->query("select value from system where name='accessPassword';");
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $value = $row['value'];
        }
        return $value;
    }

    public static function getSessionId($password)
    {
        $db = self::connectDB();
        $sessionId = "";
        $sth = $db->query("select value from system where name='accessPassword';");
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            if ($password === $row['value'])
            {
                $sessionId = hash("md5", uniqid(), false);
            }
        }
        if ($sessionId !== "")
        {
            $db = self::connectDB();
            $sth = $db->prepare("insert into accessSessionId(value) values(:value);");
            $sth->bindValue(':value', $sessionId, PDO::PARAM_STR);
            $sth->execute();
        }

        return $sessionId;
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

    public static function passwordProtectEnabled()
    {
        $db = self::connectDB();
        $value = 0;
        $sth = $db->query("select value from system where name='accessPasswordProtect';");
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

    public static function enablePasswordProtect()
    {
        $db = self::connectDB();
        $db->query("update system set value=1 where name='accessPasswordProtect';");
    }

    public static function disablePasswordProtect()
    {
        $db = self::connectDB();
        $db->query("update system set value=0 where name='accessPasswordProtect';");
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
            case 2:
                $db->query("insert into system(name,value) values('accessPasswordProtect', 0);");
                $db->query("insert into system(name,value) values('accessPassword', '');");
            case 3:
                $db->query("create table accessSessionId(id integer primary key, value);");
                $version = 4;

                $sth = $db->prepare("update system set value=:value where name='accessVersion';");
                $sth->bindValue(':value', $version, PDO::PARAM_INT);
                $sth->execute();
            default:
        }

        return $db;
	}
}
?>
