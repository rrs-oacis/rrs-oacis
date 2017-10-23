<?php

namespace rrsoacis\manager\component;


class App
{
    public $name;
    public $version;
    public $description;
    public $icon;
    public $main_controller;
    public $sub_controller;
    public $dependencies;
    public $package;
    public $packages_user;
    public $packages_name;
    public $enabled;
    public $is_plugin;

    public function __construct($rawMap)
    {
        $this->name = $rawMap["name"];
        $this->version = $rawMap["version"];
        $this->description = $rawMap["description"];
        $this->icon = $rawMap["icon"];
        $this->main_controller = $rawMap["main_controller"];
        $this->sub_controller = $rawMap["sub_controller"];
        $this->dependencies = $rawMap["dependencies"];
        $this->package = $rawMap["package"];
        $this->packages_user = $rawMap["packages_user"];
        $this->packages_name = $rawMap["packages_name"];
        $this->enabled = $rawMap["enabled"];
        $this->is_plugin = $rawMap["is_plugin"];
    }

    public static function arrayFactory($rawMapArray)
    {
        $array = array();
        foreach ($rawMapArray as $agent) {
            $array[] = new App($agent);
        }
        return $array;
    }
}