<?php
define('APPPATH',dirname(__DIR__) . '/src/');
define('SYSPATH',dirname(__DIR__));
require SYSPATH . "/vendor/autoload.php";
require SYSPATH . "/src/framework/Utility.php";
//////////Entry Point//////////
date_default_timezone_set('Asia/Tokyo');
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
$app = new \framework\Container;

$app = new Application;
$app->run();
