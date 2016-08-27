<?php

// for tests and debug
//include 'info.php';
 
//set_time_limit(0);

$loader = require_once __DIR__ . '/../vendor/autoload.php';
include '../app/hook.php';

$boot = new CHRIST\Common\Bootstrap('local', true);

$boot->run();
