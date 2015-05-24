<?php

$args = $argv;
$command = $argv[1];
$config = parse_ini_file($_SERVER['HOME'] . '/.sendgrid/config.ini');

// remove file name and first arg
array_shift($args);
array_shift($args);

$loader = include_once('vendor/autoload.php');

$commands = new Hoborglabs\Sendgrid\Commands($config);
$command = $commands->createByName($command);

exit($command->run($args));
