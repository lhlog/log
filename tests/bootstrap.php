<?php

error_reporting(E_ALL | E_STRICT);

date_default_timezone_set('Asia/Hong_Kong');

if (!file_exists(dirname(__DIR__) . '/composer.lock')) {
    die("please must be installed using composer:\n\nphp composer.phar install --dev\n\n");
}

$autoloader = require_once dirname(__DIR__) . '/vendor/autoload.php';
