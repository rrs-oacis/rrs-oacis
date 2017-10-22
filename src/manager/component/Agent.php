<?php

namespace manager\component;


class Agent
{
    public $name;
    public $alias;
    public $archived;
    public $timestamp;

    public function __construct($rawMap)
    {
        $this->name = $rawMap["name"];
        $this->alias = $rawMap["alias"];
        $this->archived = $rawMap["archived"];
        $this->timestamp = $rawMap["timestamp"];
    }

    public static function agentListFactory($rawMapArray)
    {
        $array = array();
        foreach ($rawMapArray as $agent) {
            $array[] = new Agent($agent);
        }
        return $array;
    }
}