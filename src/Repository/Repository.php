<?php

namespace App\Repository;

use APP\Db\Mysql;
use App\Models\StringTools;

class Repository
{
    protected \PDO $pdo;

    public function __construct()
    {
        $mysql = Mysql::getInstance();
        $this->pdo = $mysql->getPDO();
    }
}
