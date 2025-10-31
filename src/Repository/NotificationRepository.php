<?php

namespace App\Repository;

use App\Db\Mysql;
use PDO;

class NotificationRepository
{
    public function insert(array $data): bool
    {

        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare(
            "INSERT INTO notifications (user_id, type, message, link, is_read, created_at)
             VALUES (:user_id, :type, :message, :link, :is_read, :created_at)"
        );

        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':type' => $data['type'],
            ':message' => $data['message'],
            ':link' => $data['link'] ?? null,
            ':is_read' => $data['is_read'] ?? 0,
            ':created_at' => $data['created_at'] ?? date('Y-m-d H:i:s')
        ]);
    }

    public function findByUser(int $userId, int $limit = 10): array
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare(
            "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit"
        );
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function markAsRead(int $id): bool
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function markAllAsRead(int $userId): bool
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = :user_id");
        return $stmt->execute([':user_id' => $userId]);
    }

    public function exists(array $criteria): bool
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM notifications 
            WHERE user_id = :user_id AND type = :type AND message = :message
        ");
        $stmt->execute([
            ':user_id' => $criteria['user_id'],
            ':type' => $criteria['type'],
            ':message' => $criteria['message']
        ]);

        return $stmt->fetchColumn() > 0;
    }
}
