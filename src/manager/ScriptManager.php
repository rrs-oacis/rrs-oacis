<?php

namespace rrsoacis\manager;


class ScriptManager
{
    public static function queuePhpScript($script)
    {
        $appName = preg_replace('/^.*\/apps\/(.*)\/(.*)\/.*$/', '${2}@${1}', debug_backtrace()[0]{"file"});
        $scriptId = uniqid();
        $throwScript = "#!/usr/bin/php\n\n";
        $throwScript .= "<?php\n";
        $throwScript .= "date_default_timezone_set('asia/tokyo');\n";
        $throwScript .= "\n";
        $throwScript .= "function getDatabase() {\n";
        $throwScript .= '$db = new PDO("sqlite:/home/oacis/rrs-oacis/data/'.$appName.'.db");'."\n";
        $throwScript .= '$sth = $db->query("select * from sqlite_master where name=\'system\';");'."\n";
        $throwScript .= 'if (0 >= $sth->rowCount())'."\n";
        $throwScript .= '{ $db->query("CREATE TABLE system(name,value);"); }'."\n";
        $throwScript .= 'return $db;'."\n";
        $throwScript .= "}\n";
        $throwScript .= "function getSystemDatabase() {\n";
        $throwScript .= '$db = new PDO("sqlite:/home/oacis/rrs-oacis/data/_main.db");'."\n";
        $throwScript .= '$sth = $db->query("select * from sqlite_master where name=\'system\';");'."\n";
        $throwScript .= 'if (0 >= $sth->rowCount())'."\n";
        $throwScript .= '{ $db->query("CREATE TABLE system(name,value);"); }'."\n";
        $throwScript .= 'return $db;'."\n";
        $throwScript .= "}\n";
        $throwScript .= "\n";
        $throwScript .= $script;
        $throwScript .= "\n";
        $throwScript .= "\n";
        file_put_contents('/home/oacis/rrs-oacis/oacis-queue/scripts/'.$scriptId, $throwScript);
        exec('nohup /home/oacis/rrs-oacis/oacis-queue/main.pl '.$scriptId.' > /dev/null &');
    }

    public static function queueBashScript($script)
    {
        $scriptId = uniqid();
        $throwScript = "#!/bin/bash\n\n";
        $throwScript .= "\n";
        $throwScript .= $script;
        $throwScript .= "\n";
        $throwScript .= "\n";
        file_put_contents('/home/oacis/rrs-oacis/oacis-queue/scripts/'.$scriptId, $throwScript);
        exec('nohup /home/oacis/rrs-oacis/oacis-queue/main.pl '.$scriptId.' > /dev/null &');
    }
}