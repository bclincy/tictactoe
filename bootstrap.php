<?php
define('ROOTDIR', __DIR__);
define('BASEDIR', __DIR__ . '/public');

include_once ROOTDIR . '/vendor/autoload.php';

use Monolog\Logger; // The Logger instance
use Monolog\Handler\StreamHandler;

$logger = new Logger('tictactoe');
$logger->pushHandler(new StreamHandler(__DIR__ . '/app.log', 100));