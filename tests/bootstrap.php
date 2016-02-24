<?php

// It should define an APP_PATH
define("APP_PATH", realpath(__DIR__."/../"));
// It should Load configuration file or exit
define("CONFIG_FILE", APP_PATH . "/tests/config.yml");
if (!is_file(CONFIG_FILE)) {
    echo "Please create a valid config file : ".CONFIG_FILE." missing.\n";
    exit(1);
}
// It should load the vendor autoload
define("AUTOLOAD_FILE", APP_PATH.'/vendor/autoload.php');
if( !is_file(AUTOLOAD_FILE)){
    echo "Please run composer.\n";
    exit(1);
}
require_once AUTOLOAD_FILE; 


