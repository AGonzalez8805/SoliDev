<?php

namespace App\Repository;

use App\Db\Mysql;
use PDO;

class FavoriteRepository
{
    public function toggleFavorite(int $userId, int $snippetId): bool
    {
        $pdo = Mysql::getInstance()->getPDO();

        // Vérifie si déjà en favori
        $check = $pdo->prepare("SELECT 1 FROM favorites WHERE users_id = :users_id AND snippet_id = :snippet_id");
        $check->execute([
            ':users_id' => $userId,
            ':snippet_id' => $snippetId
        ]);

        if ($check->fetch()) {
            // Supprime le favori
            $stmt = $pdo->prepare("DELETE FROM favorites WHERE users_id = :users_id AND snippet_id = :snippet_id");
            $stmt->execute([
                ':users_id' => $userId,
                ':snippet_id' => $snippetId
            ]);
            return false;
        } else {
            // Ajoute le favori
            $stmt = $pdo->prepare("INSERT INTO favorites (users_id, snippet_id) VALUES (:users_id, :snippet_id)");
            $stmt->execute([
                ':users_id' => $userId,
                ':snippet_id' => $snippetId
            ]);
            return true;
        }
    }

    public function isFavorite(int $userId, int $snippetId): bool
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("SELECT 1 FROM favorites WHERE users_id = :users_id AND snippet_id = :snippet_id");
        $stmt->execute([
            ':users_id' => $userId,
            ':snippet_id' => $snippetId
        ]);
        return (bool) $stmt->fetch();
    }

    public function countFavorites(int $snippetId): int
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM favorites WHERE snippet_id = :snippet_id");
        $stmt->execute([':snippet_id' => $snippetId]);
        return (int) $stmt->fetchColumn();
    }
}
