<?php

$loader = require_once __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../app/hook.php';

$boot = new CHRIST\Common\Bootstrap('prod', false, $loader);

$boot->run();