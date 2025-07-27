<?php
session_start();

define('APP_ROOT', dirname(__DIR__)); // ce sera /var/www/html

define('APP_ENV', dirname(__DIR__) . '/.env');

require __DIR__ . '/../vendor/autoload.php';

use App\Controller\Controller;

$controller = new Controller();
$controller->route();

use App\Db\Mysql;

$mysql = Mysql::getInstance();
