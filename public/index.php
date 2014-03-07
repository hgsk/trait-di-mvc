<?php
date_default_timezone_set('Asia/Tokyo');
define('APPPATH',dirname(__DIR__) . '/src/');
define('SYSPATH',dirname(__DIR__));
require SYSPATH . "/vendor/autoload.php";
//////////Entry Point//////////
use Symfony\Component\Debug\Debug;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\Debug\ErrorHandler;
ErrorHandler::register();
ExceptionHandler::register();
Debug::enable();
use Monolog\Logger;
use Monolog\Handler\ConsoleLogHandler;
use Monolog\Handler\StreamHandler;
$log = new Logger('warning');
$log->pushHandler(new ConsoleLogHandler(Logger::WARNING));
$log->pushHandler(new StreamHandler(SYSPATH . "/log/monolog.log", Logger::WARNING));
$log->addWarning(SYSPATH);
$app = new framework\Container;
throw new Exception('hoge');

$app = new Application;
$app->run();
