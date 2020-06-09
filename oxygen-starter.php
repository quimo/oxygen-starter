<?php

/*
    Plugin Name: Oxygen Starter
    Description: WordPress starter plugin for oxygen builder
    Author: Simone Alati
    Version: 0.1
    Author URI: https://github.com/quimo/oxygen-starter
*/

if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . "/config/config.php";
require_once __DIR__ . "/inc/oxygen-starter.class.php";
require_once __DIR__ . "/inc/oxygen-helpers.class.php";