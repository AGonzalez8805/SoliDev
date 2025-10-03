<?php
// ----------------------------
//  RACINE DU PROJET
// ----------------------------
define('APP_ROOT', dirname(__DIR__));

// ----------------------------
//  SESSION
// ----------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ----------------------------
//  CHEMIN CONFIG DB
// ----------------------------
define('APP_CONFIG', APP_ROOT . '/.db.ini');

// ----------------------------
//  CHARGER VARIABLES ENV
// ----------------------------
// Compatible avec .env standard (clé=valeur)
$envPath = APP_ROOT . '/.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;

        [$name, $value] = explode('=', $line, 2);
        $name  = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\""); // retire guillemets et espaces
        $_ENV[$name] = $value;
        putenv("$name=$value");
    }
}

// ----------------------------
//  ENVIRONNEMENT (dev/prod)
// ----------------------------
$env = $_ENV['APP_ENV'] ?? 'prod';

if ($env === 'dev') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
    ini_set('log_errors', 1);
    ini_set('error_log', APP_ROOT . '/var/log/php_errors.log');
}

// ----------------------------
//  AUTOLOADER
// ----------------------------
require_once APP_ROOT . '/vendor/autoload.php';

// ----------------------------
//  BASES DE DONNÉES
// ----------------------------
use App\Db\Mysql;
use App\Db\Mongo;

$mysql = Mysql::getInstance()->getPDO();
$mongo = Mongo::getInstance();
$dbMongo = $mongo->getDatabase();

// ----------------------------
//  MAILER
// ----------------------------
use App\Config\Mailer;

$mailer = new Mailer(true); // true = debug SMTP en dev

// ----------------------------
//  CONTROLLER
// ----------------------------
use App\Controller\Controller;

$controller = new Controller($mailer);
$controller->route();
