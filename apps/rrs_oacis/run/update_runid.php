<?php

if ($argc != 3) { return; }

$id = ''.$argv[1];
$filename = ''.$argv[2];

$outputs = json_decode( file_get_contents($filename), true );

foreach ($outputs as $out)
{
    $paramId = $out['parameter_set_id'];
    $runId = '';

    $paramSetJson = file_get_contents('http://localhost:3000/parameter_sets/'.$paramId.'.json');
    $paramSet = json_decode($paramSetJson, true);
    foreach ($paramSet['runs'] as $run)
    {
        $runId = $run['id'];
    }

    //$db = \rrsoacis\manager\DatabaseManager::getDatabase();
    $db = new PDO('sqlite:'.dirname(__FILE__).'/../../../data/run@rrs_oacis.db');
    $sth = $db->prepare("update run set paramId=:paramId, runId=:runId where name=:name;");
    $sth->bindValue(':paramId', $paramId, PDO::PARAM_STR);
    $sth->bindValue(':runId', $runId, PDO::PARAM_STR);
    $sth->bindValue(':name', $id, PDO::PARAM_STR);
    $sth->execute();
}


