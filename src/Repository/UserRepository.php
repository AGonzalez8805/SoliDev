<?php

namespace App\Repository;

use App\Db\Mysql;

class UserRepository
{
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

    public function updatePhoto(int $userId, string $fileName): bool
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("UPDATE users SET photo = :photo WHERE users_id = :id");
        return $stmt->execute([
            ':photo' => $fileName,
            ':id' => $userId
        ]);
    }

    public function updateSocialLinks(int $userId, ?string $github, ?string $linkedin, ?string $website): bool
    {
        $sql = "UPDATE users
            SET github_url = :github, linkedin_url = :linkedin, website_url = :website
            WHERE users_id = :id";

        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':github' => $github,
            ':linkedin' => $linkedin,
            ':website' => $website,
            ':id' => $userId
        ]);
    }

    public function updateProfileDetails(int $userId, ?string $bio, ?string $skills): bool
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("
        UPDATE users
        SET bio = :bio, skills = :skills
        WHERE users_id = :id
        ");
        return $stmt->execute([
            ':bio' => $bio,
            ':skills' => $skills,
            ':id' => $userId
        ]);
    }

    public function findRecentByUser(int $userId, int $limit = 5): array
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("
            SELECT type, message, created_at
            FROM user_activities
            WHERE user_id = :user_id
            ORDER BY created_at DESC
            LIMIT $limit
        ");
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getStats(int $userId): array
    {
        $pdo = Mysql::getInstance()->getPDO();

        // Messages Forum
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_activities WHERE user_id=:user_id AND type='forum_post'");
        $stmt->execute(['user_id' => $userId]);
        $forumPosts = (int) $stmt->fetchColumn();

        // Posts Blog
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM blog WHERE author_id=:user_id AND status='published'");
        $stmt->execute(['user_id' => $userId]);
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
}
