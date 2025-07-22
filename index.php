<?php

define('_ROOTPATH_', __DIR__);

require __DIR__ . '/vendor/autoload.php';

use App\Controller\Controller;

$controller = new Controller();
$controller->route();