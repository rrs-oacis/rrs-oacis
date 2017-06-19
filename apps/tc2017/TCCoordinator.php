<?php

namespace adf\apps\tc2017;

use adf\file\AgentLoader;
use adf\file\ClusterLoader;
use adf\file\MapLoader;
use \MongoClient;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use \PDO;
use adf\Config;
use adf\Agent;
use adf\error\AgentNotFoundException;

class TCCoordinator
{
    public static function blendTeams ($baseTeamName, $tdTeamName)
    {
        $baseTeam = AgentLoader::getAgent($baseTeamName);
        $tdTeam = AgentLoader::getAgent($tdTeamName);
        if (count($baseTeam) <= 0 || count($tdTeam) <= 0)
        {
            return;
        }

        $name = $tdTeam['alias'].'_'.$baseTeam['alias'].'_'.uniqid();
        $alias = 'TC_'.$tdTeam['alias'].'_'.$baseTeam['alias'];

        $agentsDir = Config::$ROUTER_PATH. Config::AGENTS_DIR_NAME;
        $baseTeamDir = $agentsDir."/".$baseTeamName;
        $tdTeamDir = $agentsDir."/".$tdTeamName;

        $uuid = uniqid();
        $tmpDir = Config::$ROUTER_PATH.Config::TMP_DIR_NAME."/".$uuid;

        system('cp -r '.$baseTeamDir.' '.$tmpDir);
        system('cp -r '.$tdTeamDir.'/src/* '.$tmpDir.'/src/');
        system('cp '.$tdTeamDir.'/config/module.cfg '.$tmpDir.'/config/');
        system('cat '.$baseTeamDir.'/config/module.cfg >> '.$tmpDir.'/config/module.cfg');
        system('cat '.$tdTeamDir.'/config/module.cfg | awk \'/\s*Tactics(AmbulanceTeam\.Human|PoliceForce\.Road|FireBrigade\.Building)Detector\s*:/{print $0}\' >> '.$tmpDir.'/config/module.cfg');
        system('mv '.$tmpDir.' '.$agentsDir.'/'.$name);

        AgentLoader::addAgent($name, $alias);

        return AgentLoader::getAgent($name);
    }
}

