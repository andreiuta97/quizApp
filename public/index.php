<?php

ini_set('display_errors', true);

// obtain the base directory for the web application a.k.a. document root
$baseDir = dirname(__DIR__);
require $baseDir . '/vendor/autoload.php';

$salut = 'aac ';
echo $salut;

phpinfo();
