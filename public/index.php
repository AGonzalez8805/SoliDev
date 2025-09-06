<?php

// Afficher toutes les erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrer la session
session_start();

// Racine du projet
define('APP_ROOT', dirname(__DIR__));

// Chemin du fichier de config DB
define('APP_ENV', dirname(__DIR__) . '/.db.ini');

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

// Connexion à la base de données
use App\Db\Mysql;
$mysql = Mysql::getInstance()->getPDO();


// Lancer le contrôleur principal
use App\Controller\Controller;

$controller = new Controller();
$controller->route();
