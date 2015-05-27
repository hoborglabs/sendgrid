<?php

$phar = new Phar(__DIR__ . '/../sendgrid.phar', 0, 'sendgrid.phar');

$includes = [
	'sendgrid\.php',
	'src\/.*\.php',
	'vendor\/.*\.php',
	'vendor\/guzzle\/guzzle\/src\/Guzzle\/Http\/Resources\/cacert.pem',
];
$phar->buildFromDirectory(__DIR__ . '/..', '/\/(' . implode('|', $includes) . ')/');
$phar->setStub("#!/usr/bin/env php
<?php
Phar::mapPhar('sendgrid.phar');
include 'phar://sendgrid.phar/sendgrid.php';
__HALT_COMPILER();
");
