<?php

// ----------------------------
//  CONFIG PRODUCTION
// ----------------------------

// Masquer toutes les erreurs à l’écran
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// Logger les erreurs dans le log PHP (Heroku les capture automatiquement)
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php_errors.log');

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Racine du projet
define('APP_ROOT', dirname(__DIR__));

// Charger les variables du fichier .env
$envPath = dirname(__DIR__) . '/.env';
if (file_exists($envPath)) {
    $envVars = parse_ini_file($envPath);
    foreach ($envVars as $key => $value) {
        $_ENV[$key] = $value;
    }
}

// Charger l'autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// ----------------------------
//  BASE DE DONNÉES
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

$mailer = new Mailer(true);

// ----------------------------
//  LANCER LE CONTRÔLEUR PRINCIPAL
// ----------------------------
use App\Controller\Controller;

$controller = new Controller($mailer);
$controller->route();
