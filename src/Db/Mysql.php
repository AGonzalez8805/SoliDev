<?php

namespace App\Db;

class Mysql
{
    private string $dbName;
    private string $dbUser;
    private string $dbPassword;
    private string $dbPort;
    private string $dbHost;
    private ?\PDO $pdo = null;
    private static ?self $_instance = null;

    private function __construct()
    {
        $dbConf = parse_ini_file(APP_ROOT. "/" . APP_ENV);
        $this->dbHost = $dbConf["db_host"];
        $this->dbUser = $dbConf["db_user"];
        $this->dbPassword = $dbConf["db_password"];
        $this->dbPort = $dbConf["db_port"];
        $this->dbName = $dbConf["db_name"];
    }

    public static function getInstance(): self
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Mysql();
        }
        return self::$_instance = new Mysql();
    }
    public function getPDO(): \PDO
    {
        if (is_null($this->pdo)) {
            $this->pdo = new \PDO("mysql:dbname={$this->dbName};charset=utf8;host={$this->dbHost}:{$this->dbPort}", $this->dbUser, $this->dbPassword);
        }
        return $this->pdo;
    }
}