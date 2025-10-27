<?php

namespace App\Repository;

use App\Models\Snippet;
use App\Db\Mysql;
use PDO;

class SnippetRepository
{
    public function findById(int $id): ?Snippet
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("SELECT * FROM snippets WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->maprowToSnippet($data) : null;
    }

    public function findAll(): array
    {
        $pdo = Mysql::getInstance()->getPDO();

        $sql = "
            SELECT s.*, u.firstName, u.name
            FROM snippets s
            JOIN users u ON s.author_id = u.users_id
            ORDER BY s.created_at DESC
        ";
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'mapRowToSnippet'], $rows);
    }

    public function insert(Snippet $snippet): int
    {
        $pdo = Mysql::getInstance()->getPDO();

        $stmt = $pdo->prepare("
            INSERT INTO snippets
            (author_id, title, description, language, category, code, usage_example, tags, visibility, allow_comments, allow_fork)
            VALUES
            (:author_id, :title, :description, :language, :category, :code, :usage_example, :tags, :visibility, :allow_comments, :allow_fork)
        ");

        $stmt->execute([
            ':author_id'     => $snippet->getAuthorId(),
            ':title'         => $snippet->getTitle(),
            ':description'   => $snippet->getDescription(),
            ':language'      => $snippet->getLanguage(),
            ':category'      => $snippet->getCategory(),
            ':code'          => $snippet->getCode(),
            ':usage_example' => $snippet->getUsageExample(),
            ':tags'          => $snippet->getTags(),
            ':visibility'    => $snippet->getVisibility(),
            ':allow_comments' => (int)$snippet->isAllowComments(),
            ':allow_fork'    => (int)$snippet->isAllowFork(),

        ]);

        return (int)$pdo->lastInsertId();
    }

    /**
     * 🔹 Incrémente le nombre de vues
     */
    public function incrementViews(int $id): void
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("UPDATE snippets SET views = views + 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function findFilteredPaginated(?string $category, ?string $search, string $sort, int $limit, int $offset): array
    {
        $pdo = Mysql::getInstance()->getPDO();

        $sql = "SELECT s.*, u.firstName, u.name FROM snippets s JOIN users u ON s.author_id = u.users_id WHERE 1=1";
        $params = [];

        if ($category) {
            $sql .= " AND s.category = :category";
            $params[':category'] = $category;
        }

        if ($search) {
            $sql .= " AND (s.title LIKE :search OR s.description LIKE :search OR s.code LIKE :search)";
            $params[':search'] = "%$search%";
        }

        // Tri selon le filtre
        $orderBy = 's.created_at DESC';
        if ($sort === 'popular') {
            $orderBy = 's.views DESC';
        }
        $sql .= " ORDER BY $orderBy LIMIT :limit OFFSET :offset";

        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return array_map([$this, 'mapRowToSnippet'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function countFiltered(?string $category, ?string $search): int
    {
        $pdo = Mysql::getInstance()->getPDO();

        $sql = "SELECT COUNT(*) FROM snippets WHERE 1=1";
        $params = [];

        if ($category) {
            $sql .= " AND category = :category";
            $params[':category'] = $category;
        }

        if ($search) {
            $sql .= " AND (title LIKE :search OR description LIKE :search OR code LIKE :search)";
            $params[':search'] = "%$search%";
        }

        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public function findByUser(int $userId): array
    {
        $pdo = Mysql::getInstance()->getPDO();

        $stmt = $pdo->prepare("
            SELECT s.*, u.firstName, u.name
            FROM snippets s
            JOIN users u ON s.author_id = u.users_id
            WHERE s.author_id = :userId
            ORDER BY s.created_at DESC
        ");
        $stmt->execute([':userId' => $userId]);

        return array_map([$this, 'mapRowToSnippet'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function mapRowToSnippet(array $row): Snippet
    {
        $snippet = new Snippet();
        $snippet->setId((int)$row['id']);
        $snippet->setTitle($row['title']);
        $snippet->setDescription($row['description']);
        $snippet->setLanguage($row['language']);
        $snippet->setCategory($row['category']);
        $snippet->setCode($row['code']);
        $snippet->setUsageExample($row['usage_example']);
        $snippet->setTags($row['tags']);
        $snippet->setVisibility($row['visibility']);
        $snippet->setAllowComments((bool)$row['allow_comments']);
        $snippet->setAllowFork((bool)$row['allow_fork']);

        // Auteur
        $authorName = $row['firstName'] ?? $row['name'] ?? 'Anonyme';
        $snippet->setAuthorName($authorName);

        return $snippet;
    }
}
