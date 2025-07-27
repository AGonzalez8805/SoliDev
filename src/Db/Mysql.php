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
    private static ?self $_instance = null;

    /**
     * Constructeur privé pour empêcher l'instanciation directe (pattern Singleton).
     * Il lit la configuration de la base depuis un fichier INI.
     */
    private function __construct()
    {
        $dbConf = parse_ini_file(APP_ENV);


        $this->dbHost = $dbConf["db_host"];
        $this->dbUser = $dbConf["db_user"];
        $this->dbPassword = $dbConf["db_password"];
        $this->dbPort = $dbConf["db_port"];
        $this->dbName = $dbConf["db_name"];
    }


    /**
     * Retourne l'instance unique de Mysql (Singleton).
     * Crée une nouvelle instance si elle n'existe pas encore.
     */
    public static function getInstance(): self
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Mysql();
        }

        return self::$_instance;
    }

    /**
     * Retourne l'objet PDO connecté à la base de données.
     * Crée la connexion s'il n'en existe pas encore.
     */
    public function getPDO(): \PDO
    {
        if (is_null($this->pdo)) {
            $this->pdo = new \PDO(
                "mysql:dbname={$this->dbName};charset=utf8;host={$this->dbHost};port={$this->dbPort}",
                $this->dbUser,
                $this->dbPassword
            );
        }

        return $this->pdo;
    }
}
