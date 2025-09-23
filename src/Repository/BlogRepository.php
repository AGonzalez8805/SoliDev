<?php

namespace App\Repository;

use App\Models\Blog;
use App\Db\Mysql;
use PDO;

class BlogRepository
{
    public function findOneById(int $id): ?Blog
    {
        $pdo = Mysql::getInstance()->getPDO();

        $stmt = $pdo->prepare('SELECT * FROM blog WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->mapRowToBlog($row);
    }

    public function findAll(): array
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->query('SELECT * FROM blog ORDER BY created_at DESC');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'mapRowToBlog'], $rows);
    }

    public function insert(Blog $blog): int
    {
        $pdo = Mysql::getInstance()->getPDO();

        $stmt = $pdo->prepare("
            INSERT INTO blog (title, category, content, status, cover_image, excerpt)
            VALUES (:title, :category, :content, :status, :cover_image, :excerpt)
        ");

        $stmt->execute([
            ':title' => $blog->getTitle(),
            ':category' => $blog->getCategory(),
            ':content' => $blog->getContent(),
            ':status' => $blog->getStatus(),
            ':cover_image' => $blog->getCoverImage(),
            ':excerpt' => $blog->getExcerpt(),
        ]);

        return (int)$pdo->lastInsertId();
    }

    public function findFilteredPaginated(?string $category, ?string $search, string $sort, int $limit, int $offset): array
    {
        $pdo = Mysql::getInstance()->getPDO();

        $sql = "SELECT * FROM blog WHERE 1=1";
        $params = [];

        if ($category) {
            $sql .= " AND category = :category";
            $params[':category'] = $category;
        }

        if ($search) {
            $sql .= " AND (title LIKE :search OR content LIKE :search)";
            $params[':search'] = "%$search%";
        }

        // Tri selon le filtre
        $orderBy = 'created_at DESC';
        if ($sort === 'popular') {
            $orderBy = 'views DESC';       // ou champ pour popularité
        } elseif ($sort === 'commented') {
            $orderBy = 'comments_count DESC'; // ou champ pour nb de commentaires
        }
        $sql .= " ORDER BY $orderBy";

        $sql .= " LIMIT $limit OFFSET $offset";

        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $stmt->execute();
        return array_map([$this, 'mapRowToBlog'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }




    public function countFiltered(?string $category, ?string $search): int
    {
        $pdo = Mysql::getInstance()->getPDO();

        $sql = "SELECT COUNT(*) FROM blog WHERE 1=1";
        $params = [];

        if ($category) {
            $sql .= " AND category = :category";
            $params[':category'] = $category;
        }

        if ($search) {
            $sql .= " AND (title LIKE :search OR content LIKE :search)";
            $params[':search'] = "%" . $search . "%";
        }

        $stmt = $pdo->prepare($sql);

        if (isset($params[':category'])) {
            $stmt->bindValue(':category', $params[':category'], PDO::PARAM_STR);
        }
        if (isset($params[':search'])) {
            $stmt->bindValue(':search', $params[':search'], PDO::PARAM_STR);
        }

        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    private function mapRowToBlog(array $row): Blog
    {
        $blog = new Blog();
        $blog->setId((int)$row['id']);
        $blog->setTitle($row['title']);
        $blog->setCategory($row['category']);
        $blog->setContent($row['content']);
        $blog->setStatus($row['status']);
        $blog->setCoverImage($row['cover_image']);
        $blog->setAllowComments((bool)$row['allow_comments']);
        $blog->setFeatured((bool)$row['featured']);
        $blog->setExcerpt($row['excerpt']);

        return $blog;
    }
}
