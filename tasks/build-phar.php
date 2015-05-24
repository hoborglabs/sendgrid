<?php

$phar = new Phar(__DIR__ . '/../sendgrid.phar', 0, 'sendgrid.phar');

$phar->buildFromDirectory(__DIR__ . '/..', '/\/(src\/.*\.php|vendor\/.*\.php|sendgrid.php)/');
$phar->setStub("#!/usr/bin/env php
<?php
Phar::mapPhar('sendgrid.phar');
include 'phar://sendgrid.phar/sendgrid.php';
__HALT_COMPILER();
");
