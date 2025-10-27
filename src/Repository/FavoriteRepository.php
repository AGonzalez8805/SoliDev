<?php

namespace App\Repository;

use App\Db\Mysql;
use PDO;
use App\Models\Snippet;

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
            $stmt = $pdo->prepare("INSERT INTO favorites (users_id, snippet_id, created_at) VALUES (:users_id, :snippet_id, NOW())");
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

    public function getFavoritesByUser(int $userId): array
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("
            SELECT 
                s.id, 
                s.title, 
                s.description, 
                s.language,
                s.code,
                s.category,
                s.tags,
                s.views,
                s.author_id,
                s.created_at,
                u.firstName,
                u.name,
                f.created_at AS favorited_at
            FROM favorites f
            INNER JOIN snippets s ON f.snippet_id = s.id
            LEFT JOIN users u ON s.author_id = u.users_id
            WHERE f.users_id = :userId
            ORDER BY f.created_at DESC
        ");
        $stmt->execute(['userId' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => $this->mapRowToSnippet($row), $rows);
    }

    private function mapRowToSnippet(array $row): Snippet
    {
        $snippet = new Snippet();
        $snippet->setId($row['id'])
            ->setTitle($row['title'])
            ->setDescription($row['description'])
            ->setLanguage($row['language'])
            ->setCode($row['code'])
            ->setCategory($row['category'] ?? '')
            ->setTags($row['tags'] ?? '')
            ->setViews($row['views'] ?? 0)
            ->setAuthorId($row['author_id'])
            ->setAuthorName(trim(($row['firstName'] ?? 'Unknown') . ' ' . ($row['name'] ?? 'User')))
            ->setCreatedAt($row['created_at']);
        return $snippet;
    }
}
