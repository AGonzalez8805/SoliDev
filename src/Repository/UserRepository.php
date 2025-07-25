<?php

namespace App\Repository;

use App\Db\Mysql;

class UserRepository
{
    public function findByEmail(string $email)
    {
        //Appel bdd
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $query = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $query->bindValue(':email', $email, $pdo::PARAM_STR);
        $query->execute();

        $user = $query->fetch();

        return $user ?: null;
    }

    public function create(array $data): bool
    {
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $query = $pdo->prepare('
        INSERT INTO users (name, firstName, email, password, role)
        VALUES (:name, :firstName, :email, :password, :role)');

        return $query->execute([
            ':name' => $data['name'],
            ':firstName' => $data['firstName'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':role' => $data['role']

        ]);
    }
    public function findByRole(string $role)
    {
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE role = :role LIMIT 1");
        $stmt->execute(['role' => $role]);

        return $stmt->fetch();
    }
}
