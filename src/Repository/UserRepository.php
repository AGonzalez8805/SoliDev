<?php

namespace App\Repository;

use App\Db\Mysql;

class UserRepository
{
    public function findAll(): array
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->query("SELECT users_id, name, firstName, email, role, registrationDate FROM users ORDER BY users_id DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE users_id = :id");
        $stmt->execute(['id' => $id]);

        $user = $stmt->fetch();
        return $user ?: null;
    }

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
        INSERT INTO users (name, firstName, email, password, role, email_verification_token, is_email_verified)
        VALUES (:name, :firstName, :email, :password, :role, :token, :is_email_verified)
        ');

        return $query->execute([
            ':name' => $data['name'],
            ':firstName' => $data['firstName'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':role' => $data['role'],
            ':token' => $data['email_verification_token'],
            ':is_email_verified' => $data['is_email_verified'] ?? 0
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

    public function updatePhoto(int $userId, string $fileName): bool
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("UPDATE users SET photo = :photo WHERE users_id = :id");
        return $stmt->execute([
            ':photo' => $fileName,
            ':id' => $userId
        ]);
    }

    public function findRecentByUser(int $userId, int $limit = 5): array
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("
            SELECT type, message, created_at
            FROM user_activities
            WHERE users_id = :users_id
            ORDER BY created_at DESC
            LIMIT $limit
        ");
        $stmt->bindValue(':users_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getStats(int $userId): array
    {
        $pdo = Mysql::getInstance()->getPDO();

        // Messages Forum
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_activities WHERE users_id=:users_id AND type='forum_post'");
        $stmt->execute(['users_id' => $userId]);
        $forumPosts = (int) $stmt->fetchColumn();

        // Posts Blog
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM blog WHERE author_id=:users_id AND status='published'");
        $stmt->execute(['users_id' => $userId]);
        $blogPosts = (int) $stmt->fetchColumn();

        // Projets Partagés → si tu as une table projects
        $projects = 0;

        // Snippets → si tu as une table snippets
        $snippets = 0;

        return [
            'forum_posts' => $forumPosts,
            'blog_posts' => $blogPosts,
            'projects' => $projects,
            'snippets' => $snippets,
        ];
    }

    public function addActivity(int $userId, string $type, string $message): bool
    {
        $pdo = Mysql::getInstance()->getPDO();
        $sql = "INSERT INTO activities (users_id, type, message, created_at) VALUES (:users_id, :type, :message, NOW())";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':users_id' => $userId,
            ':type' => $type,
            ':message' => $message
        ]);
    }

    public function findByToken(string $token): ?array
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email_verification_token = :token LIMIT 1");
        $stmt->execute([':token' => $token]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    // Marquer un utilisateur comme vérifié
    public function verifyUser(int $userId): bool
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("
            UPDATE users
            SET is_email_verified = 1, email_verification_token = NULL
            WHERE users_id = :id
        ");
        return $stmt->execute([':id' => $userId]);
    }

    // Stats pour un utilisateur
    public function getUserStats(int $userId): array
    {
        $pdo = Mysql::getInstance()->getPDO();

        // Messages Forum
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_activities WHERE users_id=:users_id AND type='forum_post'");
        $stmt->execute(['users_id' => $userId]);
        $forumPosts = (int) $stmt->fetchColumn();

        // Posts Blog
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM blog WHERE author_id=:users_id AND status='published'");
        $stmt->execute(['users_id' => $userId]);
        $blogPosts = (int) $stmt->fetchColumn();

        // Projets
        $projects = 0;

        // Snippets
        $snippets = 0;

        return [
            'forum_posts' => $forumPosts,
            'blog_posts' => $blogPosts,
            'projects' => $projects,
            'snippets' => $snippets,
        ];
    }

    // Stats globales
    public function getGlobalStats(): array
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmtUsers = $pdo->query("SELECT COUNT(*) as count FROM users");
        $usersCount = (int) $stmtUsers->fetch()['count'];
        $stmtBlogs = $pdo->query("SELECT COUNT(*) as count FROM blog");
        $blogsCount = (int) $stmtBlogs->fetch()['count'];
        $projectsCount = 0;
        $snippetsCount = 0;
        return [
            'users' => $usersCount,
            'blogs' => $blogsCount,
            'projects' => $projectsCount,
            'snippets' => $snippetsCount,
        ];
    }

    // Suppression & modification d'un utilisateur (AdminDashboard)
    public function delete(int $userId): bool
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("DELETE FROM users WHERE users_id = :id");
        return $stmt->execute(['id' => $userId]);
    }

    public function update(int $userId, array $data): bool
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("UPDATE users SET name = :name WHERE users_id = :id");
        return $stmt->execute([
            ':id' => $userId,
            ':name' => $data['name']
        ]);
    }

    public function findAllExcept(int $excludeUserId): array
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("
            SELECT users_id, name, firstName, email, role, registrationDate
            FROM users
            WHERE users_id != :excludeId
            ORDER BY users_id DESC
        ");
        $stmt->execute(['excludeId' => $excludeUserId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getMonthlyRegistrations(): array
    {
        $months = [];
        // Initialisation des 12 mois à 0
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = 0;
        }

        // Requête pour récupérer le nombre d'utilisateurs inscrits par mois
        $sql = "SELECT MONTH(registrationDate) AS month, COUNT(*) AS count
            FROM users
            WHERE YEAR(registrationDate) = YEAR(CURDATE())
            GROUP BY MONTH(registrationDate)";
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $months[(int)$row['month']] = (int)$row['count'];
        }

        // Retourner un tableau indexé de 0 à 11 pour les 12 mois
        return array_values($months);
    }

    public function updateProfile(int $userId, array $data): bool
    {
        $pdo = Mysql::getInstance()->getPDO();

        // Champs autorisés à être mis à jour
        $allowedFields = [
            'name',
            'firstName',
            'email',
            'github_url',
            'linkedin_url',
            'website_url',
            'bio',
            'skills',
            'photo'
        ];

        $setParts = [];
        $params = [':userId' => $userId];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $setParts[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }

        if (empty($setParts)) {
            return false; // rien à mettre à jour
        }

        $sql = "UPDATE users SET " . implode(', ', $setParts) . " WHERE users_id = :userId";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute($params);
    }

    public function updatePassword(int $userId, string $hashedPassword): bool
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE users_id = :id");
        return $stmt->execute([
            ':password' => $hashedPassword,
            ':id' => $userId
        ]);
    }

    public function updatePreferences(int $userId, array $data): bool
    {
        $pdo = Mysql::getInstance()->getPDO();

        $setParts = [];
        $params = [':userId' => $userId];

        foreach ($data as $key => $value) {
            $setParts[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        if (empty($setParts)) {
            return false;
        }

        $sql = "UPDATE users SET " . implode(', ', $setParts) . " WHERE users_id = :userId";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute($params);
    }

    public function getNotifications(int $userId, int $limit = 50): array
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("
        SELECT id, type, message, link, is_read, created_at
        FROM notifications
        WHERE user_id = :userId
        ORDER BY created_at DESC
        LIMIT :limit
    ");
        $stmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function markNotificationAsRead(int $notificationId, int $userId): bool
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("
        UPDATE notifications 
        SET is_read = TRUE 
        WHERE id = :id AND user_id = :userId
    ");
        return $stmt->execute([
            ':id' => $notificationId,
            ':userId' => $userId
        ]);
    }

    public function markAllNotificationsAsRead(int $userId): bool
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("
        UPDATE notifications 
        SET is_read = TRUE 
        WHERE user_id = :userId AND is_read = FALSE
    ");
        return $stmt->execute([':userId' => $userId]);
    }

    public function getUserPreferences(int $userId): ?array
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("
        SELECT theme, language, timezone,
               profile_public, show_online_status, allow_search_indexing,
               notify_comments, notify_likes, notify_followers, notify_newsletter
        FROM users
        WHERE users_id = :id
    ");
        $stmt->execute(['id' => $userId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
