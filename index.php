<?php
session_start();

define('APP_ROOT', __DIR__);
define('APP_ENV', ".env");

require __DIR__ . '/vendor/autoload.php';

use App\Controller\Controller;

$controller = new Controller();
$controller->route();

use App\Db\Mysql;

$mysql = Mysql::getInstance();
