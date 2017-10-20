<?php
if ($argc != 2) { return; }

$name = ''.$argv[1];

$workspace = "/home/oacis/rrs-oacis/rrsenv/workspace/".$name;
exec('cd '.$workspace.'; timeout 5 ../../script/rrscluster check', $out, $ret);
$messages = implode("\n", $out);
$checkMessageArray = preg_grep("/^@.\d/", $out);

print_r($out);

$hasError = [];
$javaVer = [];
foreach (["S", "A", "F", "P"] as $alias)
{
    $hasError[$alias] = false;
    $javaVer[$alias] = "";
}

foreach ($checkMessageArray as $msg)
{
    $errorCode = preg_replace('/^@(.\d)/', '${1}', $msg);
    $node = substr($errorCode, 0, 1);
    $code = intval(substr($errorCode, 1));
    if ($code == 0)
    { $javaVer[$node] = preg_replace('/^@.\d (.+)$/', '${1}', $msg); }
    else
    { $hasError[$node] = true; }
}

$errorCount = 0;
foreach (["S", "A", "F", "P"] as $alias)
{
    if ($javaVer[$alias] == "")
    { $hasError[$alias] = true; }

    if ($hasError[$alias])
    { $errorCount++; }
}

if ($errorCount <= 0)
{
    system("sqlite3 \"/home/oacis/rrs-oacis/data/_main.db\" \"update cluster set check_status=0 where name='".$name."';\"");
}
else
{
    system("sqlite3 \"/home/oacis/rrs-oacis/data/_main.db\" \"update cluster set check_status=2 where check_status!=3 and name='".$name."';\"");
}

