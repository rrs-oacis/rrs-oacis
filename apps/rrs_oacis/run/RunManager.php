<?php

namespace rrsoacis\apps\rrs_oacis\run;

use rrsoacis\system\Config;
use rrsoacis\manager\ClusterManager;
use rrsoacis\manager\AgentManager;
use rrsoacis\manager\MapManager;
use rrsoacis\manager\DatabaseManager;

use \PDO;

class RunManager
{

    const SimulatorName = "RO_Run";

    public static function addRuns($agents, $maps, $tag, $count)
    {

        self::createRunSimulator();

        foreach ($maps as $map) {

            foreach ($agents as $agent) {

                for ($i = 0; $i < $count; $i++) {
                    self::run($agent, $map, $tag);
                }

            }

        }


    }


    public static function createRunSimulator()
    {


        $db = self::connectDB();
        $simulatorId = "";

        $sth = $db->query("select * from simulator;");
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {

            if (isset($row["name"]) && isset($row["id"])) {

                $simulatorId = $row["id"];

                $context = stream_context_create(array(
                    'http' => array('ignore_errors' => true)
                ));


                $simNow = file_get_contents('http://localhost:3000/simulators/' . $simulatorId,false, $context);

                $is_seccess = strpos($http_response_header[0], '200');
                if( $is_seccess === false ) {
                    $simulatorId = '';
                    $sthU = $db->prepare("delete from simulator where name=:name;");
                    $sthU->bindValue(':name', $row["name"], PDO::PARAM_STR);
                    $sthU->execute();
                }


            }
        }

        if ($simulatorId != "") return;

        $simulatorName = self::SimulatorName . '_' . uniqid();


        $tmpFileOut = '/tmp/rrsoacis-out-' . uniqid();
        $tmpFileIn = '/tmp/rrsoacis-in-' . uniqid();
        system("sudo -i -u oacis " . Config::$OACISCLI_PATH . " simulator_template -o " . $tmpFileOut . " 2>&1");
        $simulator = json_decode(file_get_contents($tmpFileOut), true);
        system("rm -f " . $tmpFileOut);
        $simulator['name'] = $simulatorName;
        $simulator['command'] = "/home/oacis/rrs-oacis/rrsenv/script/rrscluster run -c ../rrscluster.cfg -i ./_input.json -l ./ -lm ALL -pre";
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

        $parameter1 = [];
        $parameter1['key'] = 'UID';
        $parameter1['type'] = 'String';
        $parameter1['default'] = '';
        $parameter1['description'] = '';
        $simulator['parameter_definitions'][] = $parameter1;

        file_put_contents($tmpFileIn, json_encode($simulator));
        system("sudo -i -u oacis " . Config::$OACISCLI_PATH . " create_simulator -i " . $tmpFileIn . " -o " . $tmpFileOut);
        system("rm -f " . $tmpFileIn);

        if (file_exists($tmpFileOut)) {
            $simulatorId = json_decode(file_get_contents($tmpFileOut), true)['simulator_id'];
            system("rm -f " . $tmpFileOut);

            $db = self::connectDB();

            $sth = $db->prepare("insert into simulator(name, id) values(:name, :id);");
            $sth->bindValue(':name', $simulatorName, PDO::PARAM_STR);
            $sth->bindValue(':id', $simulatorId, PDO::PARAM_STR);
            $sth->execute();

        }


    }

    public static function run($agentName, $mapName, $tag)
    {

        $agent = AgentManager::getAgentByAlias($agentName);
        $map = MapManager::getMapByAlias($mapName);

        $db = self::connectDB();

        $simulatorId = "";

        $sth = $db->query("select * from simulator;");
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            if (isset($row["name"]) && isset($row["id"])) {
                $simulatorId = $row["id"];
            }
        }


        $scriptId = uniqid();

        $db = self::connectDB();
        $sth = $db->prepare("insert into run(simulation, name, agent, map, tag) values(:simulation, :name, :agent, :map, :tag);");
        $sth->bindValue(':simulation', $simulatorId, PDO::PARAM_STR);
        $sth->bindValue(':name', $scriptId, PDO::PARAM_STR);
        $sth->bindValue(':agent', $agent['name'], PDO::PARAM_STR);
        $sth->bindValue(':map', $map['name'], PDO::PARAM_STR);
        $sth->bindValue(':tag', $tag, PDO::PARAM_STR);
        //$sth->bindValue(':paramId', $highlight, PDO::PARAM_INT);
        //$sth->bindValue(':runId', $scriptId, PDO::PARAM_INT);
        $sth->execute();

        $script = "#!/bin/bash\n\n";
        $script .= Config::$OACISCLI_PATH . " create_parameter_sets";
        $script .= ' -s ' . $simulatorId;
        $script .= ' -i \'{"MAP":"' . $map['name'] . '","AGENT_F":"' . $agent['name'] . '","AGENT_P":"' . $agent['name'] . '","AGENT_A":"' . $agent['name'] . '","UID":"' . $scriptId . '"}\'';
        $script .= ' -r \'{"num_runs":1,"mpi_procs":0,"omp_threads":0,"priority":1,"submitted_to":"' . ClusterManager::getMainHostGroup() . '","host_parameters":null}\'';
        $script .= ' -o /tmp/out_' . $scriptId . '.json';
        $script .= "\n";
        $script .= 'php ' . realpath(dirname(__FILE__)) . '/update_runid.php \'' . $scriptId . '\' /tmp/out_' . $scriptId . '.json';
        file_put_contents('/home/oacis/rrs-oacis/oacis-queue/scripts/' . $scriptId, $script);
        exec('nohup /home/oacis/rrs-oacis/oacis-queue/main.pl ' . $scriptId . ' >/dev/null &');


    }

    public static function getRun($name)
    {

        $db = self::connectDB();
        $sth = $db->prepare("select * from run where name=:name;");
        $sth->bindValue(':name', $name, PDO::PARAM_STR);
        $sth->execute();
        $run = "";
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {

            $runRawJson = @file_get_contents('http://localhost:3000/runs/' . $row["runId"] . '.json');
            $runJson = json_decode($runRawJson, true);
            $status = $runJson['status'];

            $row["status"] = $status;
            $row["host"] = $runJson['submitted_to']['id'];

            $sthU = $db->prepare("update run set status=:status where name=:name;");
            $sthU->bindValue(':status', $status, PDO::PARAM_STR);
            $sthU->bindValue(':name', $row['name'], PDO::PARAM_STR);
            $sthU->execute();

            //score
            if ($row['status'] == 'failed' || $row['status'] == 'finished') {

                $score = self::getScores($row["simulation"], $row["paramId"], $row["runId"]);

                if ($row['status'] === 'failed' && !$score) {
                    $score = -1;

                    //Update
                    $sthU = $db->prepare("update run set score=:score where name=:name;");
                    $sthU->bindValue(':score', $score, PDO::PARAM_STR);
                    $sthU->bindValue(':name', $row['name'], PDO::PARAM_STR);
                    $sthU->execute();

                } else if ($score) {

                    //Update
                    $sthU = $db->prepare("update run set score=:score where name=:name;");
                    $sthU->bindValue(':score', $score, PDO::PARAM_STR);
                    $sthU->bindValue(':name', $row['name'], PDO::PARAM_STR);
                    $sthU->execute();

                } else if (!$score) {
                    $score = 'none';
                }


                $row["score"] = $score;

            } else {

                $row["score"] = 'none';

            }

            $run = $row;

        }

        return $run;

    }

    //TODO function
    public static function getRuns()
    {

        $db = self::connectDB();

        $sth = $db->query("select * from run;");
        $runs = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {


            if (isset($row['status']) && ($row['status'] === 'failed' || $row['status'] === 'finished')) {

                //$status = $row['status'];

            } else {


                /*
                $runRawJson = @file_get_contents('http://localhost:3000/runs/' . $row["runId"] . '.json');
                $runJson = json_decode($runRawJson, true);
                $status = $runJson['status'];

                $row["status"] = $status;


                //Update
                $sthU = $db->prepare("update run set status=:status where name=:name;");
                $sthU->bindValue(':status', $status, PDO::PARAM_STR);
                $sthU->bindValue(':name', $row['name'], PDO::PARAM_STR);
                $sthU->execute();

                */


            }


            if (!isset($row["score"])) {
                $row["score"] = 'none';
            }
            /*
            if (!isset($row["score"])) {


                $score = false;

                if ($row['status'] == 'failed' || $row['status'] == 'finished') {

                    $score = self::getScores($row["simulation"], $row["paramId"], $row["runId"]);

                    if ($row['status'] === 'failed' && !$score) {
                        $score = -1;

                        //Update
                        $sthU = $db->prepare("update run set score=:score where name=:name;");
                        $sthU->bindValue(':score', (int)$score, PDO::PARAM_INT);
                        $sthU->bindValue(':name', $row['name'], PDO::PARAM_STR);
                        $sthU->execute();

                    } else if ($score) {

                        //Update
                        $sthU = $db->prepare("update run set score=:score where name=:name;");
                        $sthU->bindValue(':score', (int)$score, PDO::PARAM_INT);
                        $sthU->bindValue(':name', $row['name'], PDO::PARAM_STR);
                        $sthU->execute();

                    } else if (!$score) {
                        $score = 'none';
                    }


                    $row["score"] = $score;

                } else {

                    $row["score"] = 'none';

                }


            }*/

            $runs[] = $row;

        }
        //data,  id agent　map　tag status score log
        //Test data
        return $runs;
    }

    public static function getScores($simulatorID, $parameterSetID, $runID)
    {
        $rawData = @file_get_contents('http://127.0.0.1:3000/Result_development/' . $simulatorID . '/' . $parameterSetID . '/' . $runID . '/' . Config::MAP_LOG . '/final-score.txt');


        //$score = round((0 + $rawData), 2);

        $score = $rawData;
        return $score;
    }

    private static function connectDB()
    {
        $db = DatabaseManager::getDatabase();
        $connectedAppVersion = 0;
        $sth = $db->query("select value from system where name='version';");
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $connectedAppVersion = $row['value'];
        }

        switch ($connectedAppVersion) {
            case 0:
                $db->query("insert into system(name,value) values('version', 1);");
                $db->query("create table simulator(name, id);");
                $db->query("create table run(simulation, name, agent, map, tag, score, paramId, runId, status, timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP);");

                $version = 1;

                $sth = $db->prepare("update system set value=:value where name='version';");
                $sth->bindValue(':value', $version, PDO::PARAM_INT);
                $sth->execute();
            default:
        }

        return $db;
    }

    public static function getDB(){
    	return self::connectDB();
    }

}
