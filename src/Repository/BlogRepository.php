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

        $stmt = $pdo->prepare("
            SELECT b.*, u.firstName, u.name
            FROM blog b
            JOIN users u ON b.author_id = u.users_id
            WHERE b.id = :id
        ");
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
        $sql = "
            SELECT b.*, u.firstName, u.name 
            FROM blog b
            JOIN users u ON b.author_id = u.users_id
            ORDER BY b.created_at DESC
        ";
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'mapRowToBlog'], $rows);
    }

    /**
     * Insère un nouvel article de blog
     * @param Blog $blog L'objet blog à insérer
     * @return int L'ID du blog inséré
     * @throws \Exception En cas d'erreur
     */
    public function insert(Blog $blog): int
    {
        $pdo = Mysql::getInstance()->getPDO();

        try {
            $stmt = $pdo->prepare("
                INSERT INTO blog (
                    author_id, 
                    title, 
                    category, 
                    content, 
                    excerpt,
                    status, 
                    cover_image,
                    created_at,
                    updated_at
                )
                VALUES (
                    :author_id, 
                    :title, 
                    :category, 
                    :content, 
                    :excerpt,
                    :status, 
                    :cover_image,
                    NOW(),
                    NOW()
                )
            ");

            $result = $stmt->execute([
                ':author_id'   => $blog->getAuthorId(),
                ':title'       => $blog->getTitle(),
                ':category'    => $blog->getCategory(),
                ':content'     => $blog->getContent(),
                ':excerpt'     => $blog->getExcerpt(),
                ':status'      => $blog->getStatus(),
                ':cover_image' => $blog->getCoverImage(),
            ]);

            if (!$result) {
                throw new \Exception("Erreur lors de l'insertion du blog");
            }

            return (int)$pdo->lastInsertId();
        } catch (\PDOException $e) {
            error_log("Erreur SQL dans BlogRepository::insert - " . $e->getMessage());
            throw new \Exception("Erreur lors de la sauvegarde de l'article : " . $e->getMessage());
        }
    }

    /**
     * Met à jour un article de blog existant
     * @param Blog $blog L'objet blog à mettre à jour
     * @return bool True si la mise à jour a réussi
     */
    public function update(Blog $blog): bool
    {
        $pdo = Mysql::getInstance()->getPDO();

        try {
            $stmt = $pdo->prepare("
                UPDATE blog 
                SET 
                    title = :title,
                    category = :category,
                    content = :content,
                    excerpt = :excerpt,
                    status = :status,
                    cover_image = :cover_image,
                    updated_at = NOW()
                WHERE id = :id
            ");

            return $stmt->execute([
                ':id'          => $blog->getId(),
                ':title'       => $blog->getTitle(),
                ':category'    => $blog->getCategory(),
                ':content'     => $blog->getContent(),
                ':excerpt'     => $blog->getExcerpt(),
                ':status'      => $blog->getStatus(),
                ':cover_image' => $blog->getCoverImage(),
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur SQL dans BlogRepository::update - " . $e->getMessage());
            return false;
        }
    }

    public function findFilteredPaginated(?string $category, ?string $search, string $sort, int $limit, int $offset): array
    {
        $pdo = Mysql::getInstance()->getPDO();

        $sql = "SELECT b.*, u.firstName, u.name FROM blog b JOIN users u ON b.author_id = u.users_id WHERE b.status = 'published'";
        $params = [];

        if ($category) {
            $sql .= " AND b.category = :category";
            $params[':category'] = $category;
        }

        if ($search) {
            $sql .= " AND (b.title LIKE :search OR b.content LIKE :search)";
            $params[':search'] = "%$search%";
        }

        // Tri selon le filtre
        $orderBy = 'b.created_at DESC';
        if ($sort === 'popular') {
            $orderBy = 'b.views DESC';
        } elseif ($sort === 'commented') {
            $orderBy = 'b.comments_count DESC';
        }
        $sql .= " ORDER BY $orderBy LIMIT $limit OFFSET $offset";

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

        $sql = "SELECT COUNT(*) FROM blog WHERE status = 'published'";
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

    /**
     * Mappe une ligne de résultat SQL vers un objet Blog
     * @param array $row La ligne de données
     * @return Blog L'objet Blog créé
     */
    private function mapRowToBlog(array $row): Blog
    {
        $blog = new Blog();

        // Champs obligatoires
        $blog->setId((int)$row['id']);
        $blog->setTitle($row['title']);
        $blog->setCategory($row['category']);
        $blog->setContent($row['content']);
        $blog->setStatus($row['status']);
        $blog->setExcerpt($row['excerpt'] ?? '');
        $blog->setAuthorId($row['author_id']);

        // Champs optionnels
        $blog->setCoverImage($row['cover_image'] ?? null);

        if (isset($row['allow_comments'])) {
            $blog->setAllowComments((bool)$row['allow_comments']);
        }

        if (isset($row['featured'])) {
            $blog->setFeatured((bool)$row['featured']);
        }

        if (isset($row['created_at'])) {
            $blog->setCreatedAt($row['created_at']);
        }

        if (isset($row['updated_at'])) {
            $blog->setUpdatedAt($row['updated_at']);
        }

        // Nom de l'auteur (jointure avec users)
        $authorName = ($row['firstName'] ?? '') . ' ' . ($row['name'] ?? '');
        $authorName = trim($authorName) ?: 'Anonyme';
        $blog->setAuthorName($authorName);

        return $blog;
    }
}
