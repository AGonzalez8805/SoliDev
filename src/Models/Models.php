<?php

namespace App\Db;

class Mysql
{
    // Propriétés de configuration extraites du fichier .env ou .ini
    private string $dbName;
    private string $dbUser;
    private string $dbPassword;
    private string $dbPort;
    private string $dbHost;

    // Objet PDO (connexion à la base de données)
    private ?\PDO $pdo = null;

    // Instance unique de Mysql pour appliquer le design pattern Singleton
    private static ?self $_instance = null;

    /**
     * Constructeur privé.
     * Empêche l’instanciation directe depuis l’extérieur de la classe.
     * Lit les paramètres de connexion dans un fichier de configuration.
     */
    private function __construct()
    {
        // Lecture de la configuration en fonction de l'environnement (APP_ENV)
        $dbConf = parse_ini_file(APP_ROOT . "/" . APP_ENV);

        // Affectation des paramètres de connexion à la base de données
        $this->dbHost     = $dbConf["db_host"];
        $this->dbUser     = $dbConf["db_user"];
        $this->dbPassword = $dbConf["db_password"];
        $this->dbPort     = $dbConf["db_port"];
        $this->dbName     = $dbConf["db_name"];
    }

    /**
     * Méthode statique qui retourne l’instance unique de la classe Mysql.
     * Elle instancie l’objet s’il n’existe pas encore.
     *
     * @return self L’unique instance de Mysql
     */
    public static function getInstance(): self
    {
        // Si aucune instance n'existe, on en crée une
        if (is_null(self::$_instance)) {
            self::$_instance = new Mysql();
        }

        // Retourne l'instance unique
        return self::$_instance;
    }

    /**
     * Fournit un objet PDO connecté à la base de données.
     * Si la connexion n'est pas encore créée, elle est initialisée ici.
     *
     * @return \PDO L’objet PDO pour effectuer les requêtes SQL
     */
    public function getPDO(): \PDO
    {
        // Si la connexion n'existe pas encore, on la crée
        if (is_null($this->pdo)) {
            $this->pdo = new \PDO(
                "mysql:dbname={$this->dbName};charset=utf8;host={$this->dbHost};port={$this->dbPort}",
                $this->dbUser,
                $this->dbPassword
            );
        }

        // Retourne l’objet PDO connecté
        return $this->pdo;
    }
}
