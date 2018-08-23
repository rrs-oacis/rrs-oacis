<?php

namespace rrsoacis\apps\rrs_oacis\competition;

use rrsoacis\manager\AgentManager;
use rrsoacis\manager\ClusterManager;
use rrsoacis\manager\DatabaseManager;
use rrsoacis\manager\MapManager;
use \MongoClient;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use \PDO;
use rrsoacis\system\Config;
use rrsoacis\system\Agent;
use rrsoacis\exception\AgentNotFoundException;

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
                $linkedAgent = AgentManager::getAgentByAlias($row2['agentAlias']);
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
                $linkedMap = MapManager::getMap($row2['map']);
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
                $linkedRuns[$row2['map']][substr($row2['agent'], 0, strlen($row2['agent'])-14)] = $row2;
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
                $linkedAgent = AgentManager::getAgentByAlias($row2['agentAlias']);
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
                $linkedMap = AgentManager::getAgentByAlias($row2['map']);
                $linkedMaps[] = $linkedMap;
            }
            $session['maps'] = $linkedMaps;
        }

        return $session;
    }

    /**
     *
     * */
    public static function addSession($name, $agents, $precursor = "", $highlight = 0)
    {
        $tmpFileOut = '/tmp/rrsoacis-out-'.uniqid();
        $tmpFileIn = '/tmp/rrsoacis-in-'.uniqid();
        system("bash -l -c '" . Config::$OACISCLI_PATH." simulator_template -o ".$tmpFileOut."' 2>&1");
        $simulator = json_decode ( file_get_contents($tmpFileOut), true );
        system("rm -f ".$tmpFileOut);
        $simulator['name'] = "RO_".preg_replace('/[^a-zA-Z0-9_]+/u', '', $name)."_".uniqid();
			  $simulator['command'] = '/home/oacis/rrs-oacis/rrsenv/script/rrscluster run -c ../rrscluster.cfg -i ./_input.json -l ./';
		    $simulator['executable_on_ids'][] = ClusterManager::getMainHostGroup();
		    $simulator['support_input_json'] = true;

        $simulator['parameter_definitions'] = [];
        $parameter1 = [];
        $parameter1['key'] = 'MAP';
        $parameter1['type'] = 'String';
        $parameter1['default'] = '';
        $parameter1['description'] = '';
        $simulator['parameter_definitions'][] = $parameter1;
        $parameter1 = [];
        $parameter1['key'] = 'AGENT_F';
        $parameter1['type'] = 'String';
        $parameter1['default'] = '';
        $parameter1['description'] = '';
        $simulator['parameter_definitions'][] = $parameter1;
        $parameter1 = [];
        $parameter1['key'] = 'AGENT_P';
        $parameter1['type'] = 'String';
        $parameter1['default'] = '';
        $parameter1['description'] = '';
        $simulator['parameter_definitions'][] = $parameter1;
        $parameter1 = [];
        $parameter1['key'] = 'AGENT_A';
        $parameter1['type'] = 'String';
        $parameter1['default'] = '';
        $parameter1['description'] = '';
        $simulator['parameter_definitions'][] = $parameter1;

        file_put_contents($tmpFileIn, json_encode($simulator));
        system("bash -l -c '" . Config::$OACISCLI_PATH." create_simulator -i ".$tmpFileIn." -o ".$tmpFileOut . "'");
        system("rm -f ".$tmpFileIn);
        $simulatorId = json_decode ( file_get_contents($tmpFileOut), true )['simulator_id'];
        system("rm -f ".$tmpFileOut);

        $db = self::connectDB();
        $sth = $db->prepare("insert into session(name, alias, precursor, highlight) values(:name, :alias, :precursor, :highlight);");
        $sth->bindValue(':name', $simulatorId, PDO::PARAM_STR);
        $sth->bindValue(':alias', $name, PDO::PARAM_STR);
        $sth->bindValue(':precursor', $precursor, PDO::PARAM_STR);
        $sth->bindValue(':highlight', $highlight, PDO::PARAM_INT);
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
        if (count(MapManager::getMap($mapName)) <= 0) { return false; }
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
            $script .= ' -i \'{"MAP":"'.$mapName.'","AGENT_F":"'.$agent['name'].'","AGENT_P":"'.$agent['name'].'","AGENT_A":"'.$agent['name'].'"}\'';
            $script .= ' -r \'{"num_runs":1,"mpi_procs":0,"omp_threads":0,"priority":1,"submitted_to":"'.ClusterManager::getMainHostGroup().'","host_parameters":null}\'';
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
        if (count(MapManager::getMap($mapName)) <= 0) { return false; }
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
    public static function repost($sessionName, $mapName, $agentName)
    {
        $session = Self::getSession($sessionName);
        if (count(MapManager::getMap($mapName)) <= 0) { return false; }
        if (count($session) <= 0) { return false; }

        $db = self::connectDB();

	$scriptId = uniqid();
	$sth = $db->prepare("insert into run(name, session, map, agent) values(:name, :session, :map, :agent);");
	$sth->bindValue(':name', $scriptId, PDO::PARAM_STR);
	$sth->bindValue(':session', $sessionName, PDO::PARAM_STR);
	$sth->bindValue(':map', $mapName, PDO::PARAM_STR);
	$sth->bindValue(':agent', $agentName, PDO::PARAM_STR);
	$sth->execute();

	$script = "#!/bin/bash\n\n";
	$script .= Config::$OACISCLI_PATH." create_parameter_sets";
	$script .= ' -s '.$sessionName;
	$script .= ' -i \'{"MAP":"'.$mapName.'","AGENT_F":"'.$agentName.'","AGENT_P":"'.$agentName.'","AGENT_A":"'.$agentName.'"}\'';
	$script .= ' -r \'{"num_runs":1,"mpi_procs":0,"omp_threads":0,"priority":1,"submitted_to":"'.ClusterManager::getMainHostGroup().'","host_parameters":null}\'';
	$script .= ' -o /tmp/out_'.$scriptId.'.json';
	$script .= "\n";
	$script .= 'php '.realpath(dirname(__FILE__)).'/update_runid.php \''.$scriptId.'\' /tmp/out_'.$scriptId.'.json';
	file_put_contents('/home/oacis/rrs-oacis/oacis-queue/scripts/'.$scriptId, $script);
	exec('nohup /home/oacis/rrs-oacis/oacis-queue/main.pl '.$scriptId.' > /dev/null &');

        return true;
    }

    /**
     *
     * */
    public static function rerun($runId)
    {
        $db = self::connectDB();
        $sth = $db->prepare("select runId,paramId,agent,map,session from run where runId=:runId;");
        $sth->bindValue(':runId', $runId, PDO::PARAM_STR);
        $sth->execute();
        while($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            if (!isset($row['agent']) || $row['agent'] == '') { continue; }
            
            $scriptId = uniqid();

            $sth = $db->prepare("update run set name=:name, agent=:agent where runId=:runId;");
            $sth->bindValue(':name', $scriptId, PDO::PARAM_STR);
            $sth->bindValue(':runId', $runId, PDO::PARAM_STR);
            $sth->bindValue(':agent', $row['agent'], PDO::PARAM_STR);
            $sth->execute();

            $script = "#!/bin/bash\n\n";
            $script .= "/home/oacis/oacis/bin/oacis_ruby /home/oacis/rrs-oacis/ruby/discard.rb ".$row['paramId'];
            $script .= "\n";
            $script .= "sleep 5";
            $script .= "\n";
            $script .= Config::$OACISCLI_PATH." create_parameter_sets";
            $script .= ' -s '.$row['session'];
            $script .= ' -i \'{"MAP":"'.$row['map'].'","AGENT_F":"'.$row['agent'].'","AGENT_P":"'.$row['agent'].'","AGENT_A":"'.$row['agent'].'"}\'';
            $script .= ' -r \'{"num_runs":1,"mpi_procs":0,"omp_threads":0,"priority":1,"submitted_to":"'.ClusterManager::getMainHostGroup().'","host_parameters":null}\'';
            $script .= ' -o /tmp/out_'.$scriptId.'.json >/tmp/e 2>&1';
            $script .= "\n";
            $script .= 'php '.realpath(dirname(__FILE__)).'/update_runid.php \''.$scriptId.'\' /tmp/out_'.$scriptId.'.json';
            file_put_contents('/home/oacis/rrs-oacis/oacis-queue/scripts/'.$scriptId, $script);
            exec('nohup /home/oacis/rrs-oacis/oacis-queue/main.pl '.$scriptId.' > /dev/null &');
        }

        return true;
    }

/*
	teamScores => [
		'agent' => score,
		'agent' => score,
	]
*/
    public static function addPresentation($teamScores, $session)
    {
		$db = self::connectDB();


		print_r ($teamScores);
		// get session
		$sessions = self::getSessions($session);
		if (count($session) <= 0) { return; }

		// get Presentation
		$present = self::getPresentations();
		$prepare = null;
		if (array_key_exists($session, $present)) {
			// Update Items
			$prepare = $db->prepare("UPDATE present SET score=:score WHERE agent=:agent AND session=:session");
		}
		else {
			// update presentation points
			$prepare = $db->prepare("INSERT INTO present(agent, session, score) VALUES(:agent, :session, :score)");
		}
		foreach ($teamScores as $team => $score) {
			$prepare->bindValue(':session', $session, PDO::PARAM_STR);
			$prepare->bindValue(':agent', $team, PDO::PARAM_STR);
			$prepare->bindValue(':score', $score, PDO::PARAM_INT);
			$prepare->execute();
		}
    }

	public static function getPresentations() {
		$db = self::connectDB();
		$prepare = $db->query("SELECT * FROM present;");
		$teamScores = [];
		while ($row = $prepare->fetch(PDO::FETCH_ASSOC)) {
			$score = $row['score'];
			$session = $row['session'];
			$teamScores[$session][$row['agent']] = $score;
			/*
				{
					'session' => [
						'agent' => score,
						'agent' => score,
					]
				}
			*/
		}

		return $teamScores;
	}

    /**
     *
     * */
    private static function connectDB()
    {
        $db = DatabaseManager::getDatabase();
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
	    case 4:
		$db->query("create table present(agent, session, score);");
	    case 5:
		$db->query("alter table session add highlight;");
		$version = 6;

                $sth = $db->prepare("update system set value=:value where name='version';");
                $sth->bindValue(':value', $version, PDO::PARAM_INT);
                $sth->execute();
            default:
        }

        return $db;
    }
}

