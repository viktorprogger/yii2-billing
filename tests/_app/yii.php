#!/usr/bin/env php
<?php

require dirname(__DIR__) . '/_bootstrap.php';

defined('STDIN') or define('STDIN', fopen('php://stdin', 'rb'));
defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'wb'));

$config = require(__DIR__ . '/config/console.php');

/** @noinspection PhpUnhandledExceptionInspection */
$exitCode = (new yii\console\Application($config))->run();
exit($exitCode);
