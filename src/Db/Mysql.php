<?php

namespace App\Db;

class Mysql
{
    // Propriétés de configuration de la base de données
    private string $dbName;
    private string $dbUser;
    private string $dbPassword;
    private string $dbPort;
    private string $dbHost;

    // Instance PDO pour la connexion à la base
    private ?\PDO $pdo = null;

    // Instance unique de la classe (singleton)
    private static ?self $instance = null;

    //Constructeur privé pour empêcher l'instanciation directe (pattern Singleton).
    private function __construct()
    {
        //Récupération de la config (pour heroku)
        $databaseUrl = getenv('DATABASE_URL') ?: getenv('JAWSDB_URL');

        if ($databaseUrl) {
            $url = parse_url($databaseUrl);
            $this->dbHost = $url["host"];
            $this->dbUser = $url["user"];
            $this->dbPassword = $url["pass"];
            $this->dbPort = $url["port"] ?? 3306;
            $this->dbName = ltrim($url["path"], '/');
        } else {
            // Sinon on charge depuis le fichier ini
            $ini = parse_ini_file(APP_ENV);
            $this->dbHost = $ini['db_host'];
            $this->dbUser = $ini['db_user'];
            $this->dbPassword = $ini['db_password'];
            $this->dbPort = $ini['db_port'] ?? 3306;
            $this->dbName = $ini['db_name'];
        }
    }

    //Retourne l'instance unique de Mysql (Singleton).
    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new Mysql();
        }

        return self::$instance;
    }

    //Retourne l'objet PDO connecté à la base de données.
    public function getPDO(): \PDO
    {
        if (is_null($this->pdo)) {
            $this->pdo = new \PDO(
                "mysql:dbname={$this->dbName};charset=utf8;host={$this->dbHost};port={$this->dbPort}",
                $this->dbUser,
                $this->dbPassword,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,  // Active les exceptions sur erreurs SQL
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, // Mode de fetch par défaut
                ]
            );
        }

        return $this->pdo;
    }
}
