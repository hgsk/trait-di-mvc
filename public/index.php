<?php
date_default_timezone_set('Asia/Tokyo');
define('APPPATH',dirname(__DIR__) . '/src/');
define('SYSPATH',dirname(__DIR__));
require SYSPATH . "/vendor/autoload.php";

// Error Handling


// Auth

//////////Entry Point//////////
$app = new framework\Container;

$app = new Application;
$app->run();
