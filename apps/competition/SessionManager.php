<?php

namespace adf\apps\competition;

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

class SessionManager
{
	/**
	 *
	 * */
    public static function getSessions()
    {
        $db = self::connectDB();

        $sth = $db->query("select * from session;");
        $sessions = [];
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $sth2 = $db->prepare("select * from linkedAgent where session=:session;");
            $sth2->bindValue(':session', $row['name'], PDO::PARAM_STR);
            $sth2->execute();
            $linkedAgents = [];
            while($row2 = $sth2->fetch(PDO::FETCH_ASSOC))
            {
                $linkedAgent = AgentLoader::getAgentByAlias($row2['agentAlias']);
                $linkedAgent['alias'] = $row2['agentAlias'];
                $linkedAgents[] = $linkedAgent;
            }
            $row['agents'] = $linkedAgents;

            $sth2 = $db->prepare("select * from linkedMap where session=:session;");
            $sth2->bindValue(':session', $row['name'], PDO::PARAM_STR);
            $sth2->execute();
            $linkedMaps = [];
            while($row2 = $sth2->fetch(PDO::FETCH_ASSOC))
            {
                $linkedMap = MapLoader::getMap($row2['map']);
                $linkedMaps[] = $linkedMap;
            }
            $row['maps'] = $linkedMaps;

            $sth2 = $db->prepare("select * from run where session=:session;");
            $sth2->bindValue(':session', $row['name'], PDO::PARAM_STR);
            $sth2->execute();
            $linkedRuns = [];
            while($row2 = $sth2->fetch(PDO::FETCH_ASSOC))
            {
                if (! isset($linkedRuns[$row2['map']]))
                {
                    $linkedRuns[$row2['map']] = [];
                }
                $linkedRuns[$row2['map']][$row2['agent']] = $row2;
            }
            $row['runs'] = $linkedRuns;

            $sessions[] = $row;
        }

		return $sessions;
	}

    /**
     *
     * */
    public static function getSession($name)
    {
        $db = self::connectDB();

        $sth = $db->prepare("select * from session where name=:name;");
        $sth->bindValue(':name', $name, PDO::PARAM_STR);
        $sth->execute();
        $session = [];
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $session = $row;

            $sth2 = $db->prepare("select * from linkedAgent where session=:session;");
            $sth2->bindValue(':session', $row['name'], PDO::PARAM_STR);
            $sth2->execute();
            $linkedAgents = [];
            while($row2 = $sth2->fetch(PDO::FETCH_ASSOC))
            {
                $linkedAgent = AgentLoader::getAgentByAlias($row2['agentAlias']);
                $linkedAgent['alias'] = $row2['agentAlias'];
                $linkedAgents[] = $linkedAgent;
            }
            $session['agents'] = $linkedAgents;

            $sth2 = $db->prepare("select * from linkedMap where session=:session;");
            $sth2->bindValue(':session', $row['name'], PDO::PARAM_STR);
            $sth2->execute();
            $linkedMaps = [];
            while($row2 = $sth2->fetch(PDO::FETCH_ASSOC))
            {
                $linkedMap = AgentLoader::getAgentByAlias($row2['map']);
                $linkedMaps[] = $linkedMap;
            }
            $session['maps'] = $linkedMaps;
        }

        return $session;
    }

    /**
     *
     * */
    public static function addSession($name, $agents, $precursor = "")
    {
        $tmpFileOut = '/tmp/rrsoacis-out-'.uniqid();
        $tmpFileIn = '/tmp/rrsoacis-in-'.uniqid();
        system("sudo -i -u oacis ".Config::$OACISCLI_PATH." simulator_template -o ".$tmpFileOut." 2>&1");
        $simulator = json_decode ( file_get_contents($tmpFileOut), true );
        system("rm -f ".$tmpFileOut);
        $simulator['name'] = "RO_".$name."_".uniqid();
        $simulator['command'] = "/home/oacis/rrs-oacis/rrsenv/script/runNGC_MT.sh";
        $simulator['executable_on_ids'][] = ClusterLoader::getMainHostGroup();

        $simulator['parameter_definitions'] = [];
        $parameter1 = [];
        $parameter1['key'] = 'MAP';
        $parameter1['type'] = 'String';
        $parameter1['default'] = '';
        $parameter1['description'] = '';
        $simulator['parameter_definitions'][] = $parameter1;
        $parameter1 = [];
        $parameter1['key'] = 'F';
        $parameter1['type'] = 'String';
        $parameter1['default'] = '';
        $parameter1['description'] = '';
        $simulator['parameter_definitions'][] = $parameter1;
        $parameter1 = [];
        $parameter1['key'] = 'P';
        $parameter1['type'] = 'String';
        $parameter1['default'] = '';
        $parameter1['description'] = '';
        $simulator['parameter_definitions'][] = $parameter1;
        $parameter1 = [];
        $parameter1['key'] = 'A';
        $parameter1['type'] = 'String';
        $parameter1['default'] = '';
        $parameter1['description'] = '';
        $simulator['parameter_definitions'][] = $parameter1;

        file_put_contents($tmpFileIn, json_encode($simulator));
        system("sudo -i -u oacis ".Config::$OACISCLI_PATH." create_simulator -i ".$tmpFileIn." -o ".$tmpFileOut);
        system("rm -f ".$tmpFileIn);
        $simulatorId = json_decode ( file_get_contents($tmpFileOut), true )['simulator_id'];
        system("rm -f ".$tmpFileOut);

        $db = self::connectDB();
        $sth = $db->prepare("insert into session(name, alias, precursor) values(:name, :alias, :precursor);");
        $sth->bindValue(':name', $simulatorId, PDO::PARAM_STR);
        $sth->bindValue(':alias', $name, PDO::PARAM_STR);
        $sth->bindValue(':precursor', $precursor, PDO::PARAM_STR);
        $sth->execute();

        foreach ($agents as $agent)
        {
            $sth = $db->prepare("insert into linkedAgent(session, agentAlias) values(:session, :agentAlias);");
            $sth->bindValue(':session', $simulatorId, PDO::PARAM_STR);
            $sth->bindValue(':agentAlias', $agent, PDO::PARAM_STR);
            $sth->execute();
        }
    }

    /**
     *
     * */
    public static function addMap($sessionName, $mapName)
    {
        $session = Self::getSession($sessionName);
        if (count(MapLoader::getMap($mapName)) <= 0) { return false; }
        if (count($session) <= 0) { return false; }

        $db = self::connectDB();
        $sth = $db->prepare("insert into linkedMap(session, map) values(:session, :map);");
        $sth->bindValue(':session', $sessionName, PDO::PARAM_STR);
        $sth->bindValue(':map', $mapName, PDO::PARAM_STR);
        $sth->execute();

        foreach ($session['agents'] as $agent)
        {
            if (!isset($agent['name']) || $agent['name'] == '') { continue; }
            
            $scriptId = uniqid();
            $sth = $db->prepare("insert into run(name, session, map, agent) values(:name, :session, :map, :agent);");
            $sth->bindValue(':name', $scriptId, PDO::PARAM_STR);
            $sth->bindValue(':session', $sessionName, PDO::PARAM_STR);
            $sth->bindValue(':map', $mapName, PDO::PARAM_STR);
            $sth->bindValue(':agent', $agent['name'], PDO::PARAM_STR);
            $sth->execute();

            $script = "#!/bin/bash\n\n";
            $script .= Config::$OACISCLI_PATH." create_parameter_sets";
            $script .= ' -s '.$sessionName;
            $script .= ' -i \'{"MAP":"'.$mapName.'","F":"'.$agent['name'].'","P":"'.$agent['name'].'","A":"'.$agent['name'].'"}\'';
            $script .= ' -r \'{"num_runs":1,"mpi_procs":0,"omp_threads":0,"priority":1,"submitted_to":"'.ClusterLoader::getMainHostGroup().'","host_parameters":null}\'';
            $script .= ' -o /tmp/out_'.$scriptId.'.json';
            $script .= "\n";
            $script .= 'php '.realpath(dirname(__FILE__)).'/update_runid.php \''.$scriptId.'\' /tmp/out_'.$scriptId.'.json';
            file_put_contents('/home/oacis/rrs-oacis/oacis-queue/scripts/'.$scriptId, $script);
            exec('nohup /home/oacis/rrs-oacis/oacis-queue/main.pl '.$scriptId.' > /dev/null &');
        }

        return true;
    }

    /**
     *
     * */
    public static function removeMap($sessionName, $mapName)
    {
        $session = Self::getSession($sessionName);
        if (count(MapLoader::getMap($mapName)) <= 0) { return false; }
        if (count($session) <= 0) { return false; }

        $db = self::connectDB();
        $sth = $db->prepare("select runId from run where session=:session and map=:map;");
        $sth->bindValue(':session', $sessionName, PDO::PARAM_STR);
        $sth->bindValue(':map', $mapName, PDO::PARAM_STR);
        $sth->execute();
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $scriptId = uniqid();

            $script = "#!/bin/bash\n\n";
            $script .= Config::$OACISCLI_PATH." destroy_runs_by_ids";
            $script .= ' '.$row['runId'];

            file_put_contents('/home/oacis/rrs-oacis/oacis-queue/scripts/'.$scriptId, $script);
            exec('nohup /home/oacis/rrs-oacis/oacis-queue/main.pl '.$scriptId.' > /dev/null &');
        }
        $db->query("delete from run where session='".$sessionName."' and map='".$mapName."';");

        $sth = $db->prepare("delete from linkedMap where session=:session and map=:map;");
        $sth->bindValue(':session', $sessionName, PDO::PARAM_STR);
        $sth->bindValue(':map', $mapName, PDO::PARAM_STR);
        $sth->execute();

        return true;
    }

    /**
     *
     * */
    public static function rerun($runId)
    {
        $db = self::connectDB();
        $sth = $db->prepare("select runId from run where runId=:runId;");
        $sth->bindValue(':runId', $runId, PDO::PARAM_STR);
        $sth->execute();
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $scriptId = uniqid();

            $script = "#!/bin/bash\n\n";
            $script .= Config::$OACISCLI_PATH." replace_runs_by_ids";
            $script .= ' '.$row['runId'];
            $script .= "\n";
            $script .= 'php '.realpath(dirname(__FILE__)).'/update_runid.php \''.$scriptId.'\' /tmp/out_'.$scriptId.'.json';
            file_put_contents('/home/oacis/rrs-oacis/oacis-queue/scripts/'.$scriptId, $script);
            exec('nohup /home/oacis/rrs-oacis/oacis-queue/main.pl '.$scriptId.' > /dev/null &');
        }

        return true;
    }

    /**
     *
     * */
    private static function connectDB()
    {
        $db = new PDO('sqlite:'.dirname(__FILE__).'/app.db');
        $connectedAppVersion = 0;
        $sth = $db->query("select value from system where name='version';");
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $connectedAppVersion = $row['value'];
        }

        switch ($connectedAppVersion)
        {
            case 0:
                $db->query("insert into system(name,value) values('version', 1);");
                $db->query("create table session(name, alias, precursor);");
                $db->query("create table linkedAgent(session, agentAlias);");
            case 1:
                $db->query("create table linkedMap(session, map);");
            case 2:
                $db->query("create table run(session, map, agent, paramId, runId);");
            case 3:
                $db->query("alter table run add name;");
                $version = 4;

                $sth = $db->prepare("update system set value=:value where name='version';");
                $sth->bindValue(':value', $version, PDO::PARAM_INT);
                $sth->execute();
            default:
        }

        return $db;
    }
}

