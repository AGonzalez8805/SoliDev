<?php

namespace App\Repository;

use App\Models\CommentsBlog; // ✅ bon modèle
use App\Db\Mysql;
use PDO;

class CommentsBlogRepository extends Repository
{
    /**
     * Récupère tous les commentaires d'un article
     * @param int $blogId L'ID de l'article
     * @return array Liste des commentaires
     */
    public function findByBlogId(int $blogId): array
    {
        $pdo = Mysql::getInstance()->getPDO();

        try {
            $stmt = $pdo->prepare("
                SELECT 
                    c.*,
                    u.firstName,
                    u.name,
                    u.photo
                FROM commentsBlog c
                JOIN users u ON c.user_id = u.users_id
                WHERE c.blog_id = :blog_id
                ORDER BY c.created_at DESC
            ");

            $stmt->execute(['blog_id' => $blogId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'mapRowToComment'], $rows);
        } catch (\PDOException $e) {
            error_log("Erreur SQL dans CommentsBlogRepository::findByBlogId - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Compte le nombre de commentaires d'un article
     * @param int $blogId L'ID de l'article
     * @return int Nombre de commentaires
     */
    public function countByBlogId(int $blogId): int
    {
        $pdo = Mysql::getInstance()->getPDO();

        try {
            $stmt = $pdo->prepare("
                SELECT COUNT(*) 
                FROM commentsBlog 
                WHERE blog_id = :blog_id
            ");

            $stmt->execute(['blog_id' => $blogId]);
            return (int)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log("Erreur SQL dans CommentsBlogRepository::countByBlogId - " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Insère un nouveau commentaire dans la base
     */
    public function insert(CommentsBlog $comment): int
    {
        $pdo = Mysql::getInstance()->getPDO();

        try {
            $stmt = $pdo->prepare("
                INSERT INTO commentsBlog (blog_id, user_id, content, created_at, updated_at)
                VALUES (:blog_id, :user_id, :content, NOW(), NOW())
            ");

            $result = $stmt->execute([
                ':blog_id'  => $comment->getBlogId(),
                ':user_id'  => $comment->getUserId(),
                ':content'  => $comment->getContent()
            ]);

            if (!$result) {
                throw new \Exception("Erreur lors de l'insertion du commentaire");
            }

            return (int)$pdo->lastInsertId();
        } catch (\PDOException $e) {
            error_log("Erreur SQL dans CommentsBlogRepository::insert - " . $e->getMessage());
            throw new \Exception("Erreur lors de la sauvegarde du commentaire : " . $e->getMessage());
        }
    }

    /**
     * Supprime un commentaire d'un article
     */
    public function delete(int $id, int $userId): bool
    {
        $pdo = Mysql::getInstance()->getPDO();

        try {
            $stmt = $pdo->prepare("
                DELETE FROM commentsBlog 
                WHERE id = :id AND user_id = :user_id
            ");

            return $stmt->execute([
                ':id' => $id,
                ':user_id' => $userId
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur SQL dans CommentsBlogRepository::delete - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mappe une ligne SQL vers un objet CommentsBlog
     */
    private function mapRowToComment(array $row): CommentsBlog
    {
        $comment = new CommentsBlog();

        $comment->setId((int)$row['id']);
        $comment->setBlogId((int)$row['blog_id']);
        $comment->setUserId((int)$row['user_id']);
        $comment->setContent($row['content']);
        $comment->setCreatedAt($row['created_at'] ?? null);
        $comment->setUpdatedAt($row['updated_at'] ?? null);

        // Informations utilisateur (jointure)
        $comment->setUserFirstName($row['firstName'] ?? null);
        $comment->setUserName($row['name'] ?? null);
        $comment->setUserPhoto($row['photo'] ?? null);

        return $comment;
    }
}
