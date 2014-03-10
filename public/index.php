<?php
date_default_timezone_set('Asia/Tokyo');
define('APPPATH',dirname(__DIR__) . '/src/');
define('SYSPATH',dirname(__DIR__));
require SYSPATH . "/vendor/autoload.php";

// Error Handling
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

// Logger 
use Monolog\Logger;
use Monolog\Handler\ConsoleLogHandler;
use Monolog\Handler\StreamHandler;
$log = new Logger('warning');
$log->pushHandler(new ConsoleLogHandler(Logger::WARNING));
$log->pushHandler(new StreamHandler(SYSPATH . "/log/monolog.log", Logger::WARNING));
$log->addWarning(SYSPATH);

// Auth

//////////Entry Point//////////
$app = new framework\Container;

$app = new Application;
$app->run();
