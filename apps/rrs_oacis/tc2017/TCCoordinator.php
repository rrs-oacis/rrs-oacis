<?php

namespace rrsoacis\apps\rrs_oacis\tc2017;

use rrsoacis\manager\AgentManager;
use rrsoacis\manager\ClusterManager;
use rrsoacis\manager\MapManager;
use \MongoClient;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use \PDO;
use rrsoacis\system\Config;
use rrsoacis\system\Agent;
use rrsoacis\exception\AgentNotFoundException;

class TCCoordinator
{
    public static function blendTeams ($baseTeamName, $tdTeamName)
    {
        $baseTeam = AgentManager::getAgent($baseTeamName);
        $tdTeam = AgentManager::getAgent($tdTeamName);
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
        system('cp -r '.$tdTeamDir.'/lib/ '.$tmpDir.'/lib');
        system('cp '.$tdTeamDir.'/config/module.cfg '.$tmpDir.'/config/');
        system('cat '.$baseTeamDir.'/config/module.cfg >> '.$tmpDir.'/config/module.cfg');
        system('cat '.$tdTeamDir.'/config/module.cfg | awk \'/[\t ]*Tactics(AmbulanceTeam\.Human|PoliceForce\.Road|FireBrigade\.Building)Detector[\t ]*:/{print $0}\' >> '.$tmpDir.'/config/module.cfg');
        system('mv '.$tmpDir.' '.$agentsDir.'/'.$name);

        AgentManager::addAgent($name, $alias);

        return AgentManager::getAgent($name);
    }
}

