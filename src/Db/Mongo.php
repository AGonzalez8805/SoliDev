<?php

namespace App\Db;

use MongoDB\Client;
use MongoDB\Database;

class Mongo
{
    private string $dbName;
    private ?Client $client = null;
    private static ?self $instance = null;

    private function __construct()
    {
        // Priorité : MONGODB_URI (prod)
        $mongoUrl = getenv('MONGO_URI');

        if ($mongoUrl) {
            // Configuration prod
            $this->client = new Client($mongoUrl);
            $this->dbName = ltrim(parse_url($mongoUrl, PHP_URL_PATH), '/');
            return;
        }
        // Configuration développement
        if (!defined('APP_ENV') || !file_exists(APP_ENV)) {
            throw new \Exception("Le fichier de configuration APP_ENV est introuvable !");
        }

        $dbConf = parse_ini_file(APP_ENV);
        $dbHost = $dbConf["mongo_host"] ?? 'localhost';
        $dbPort = $dbConf["mongo_port"] ?? 27017;
        $dbUser = rawurlencode($dbConf["mongo_user"] ?? '');
        $dbPassword = rawurlencode($dbConf["mongo_password"] ?? '');
        $this->dbName = $dbConf["mongo_name"] ?? 'test';

        // Construction de l'URI avec authSource=admin
        $mongoUrl = "mongodb://{$dbUser}:{$dbPassword}@{$dbHost}:{$dbPort}/{$this->dbName}?authSource=admin";
        $this->client = new Client($mongoUrl);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getDatabase(): Database
    {
        return $this->client->selectDatabase($this->dbName);
    }
}
