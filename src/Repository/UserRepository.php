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
        INSERT INTO users (nom, prenom, email, mot_de_passe, rôle)
        VALUES (:nom, :prenom, :email, :mot_de_passe, :role)');

        return $query->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':mot_de_passe' => $data['mot_de_passe'],
            ':role' => 'utilisateur'

        ]);
    }
}
