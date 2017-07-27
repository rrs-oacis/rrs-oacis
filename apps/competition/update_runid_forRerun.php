<?php

if ($argc != 2) { return; }

$paramId = ''.$argv[1];

$runId = '';

$paramSetJson = file_get_contents('http://localhost:3000/parameter_sets/'.$paramId.'.json');
$paramSet = json_decode($paramSetJson, true);
foreach ($paramSet['runs'] as $run)
{
	$runId = $run['id'];
}

$db = new PDO('sqlite:'.dirname(__FILE__).'/app.db');
$sth = $db->prepare("update run set runId=:runId where paramId=:paramId;");
$sth->bindValue(':paramId', $paramId, PDO::PARAM_STR);
$sth->bindValue(':runId', $runId, PDO::PARAM_STR);
$sth->execute();

echo $runId.'\n';
