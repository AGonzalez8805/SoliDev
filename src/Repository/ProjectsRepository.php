<?php

namespace App\Repository;

use App\Db\Mysql;
use App\Models\Project;

class ProjectsRepository
{
    public function findAll(?string $status = null, ?string $tech = null, ?string $search = null, ?string $sort = 'recent', ?string $teamSize = null): array
    {
        $pdo = Mysql::getInstance()->getPDO();

        $sql = "
            SELECT p.*, u.firstName AS owner_firstname,
            (SELECT COUNT(*) FROM project_collaborators pc WHERE pc.project_id = p.id) AS collaborators_count
            FROM projects p
            JOIN users u ON p.owner_id = u.users_id
            WHERE 1=1
        ";

        $params = [];

        // Filtre par statut
        if ($status) {
            $sql .= " AND p.status = :status";
            $params['status'] = $status;
        }

        // Filtre par technologie (JSON)
        if ($tech) {
            $sql .= " AND JSON_CONTAINS(p.technologies, :tech_json)";
            $params['tech_json'] = json_encode($tech);
        }

        // Filtre par recherche
        if ($search) {
            $sql .= " AND (p.title LIKE :search OR p.description LIKE :search)";
            $params['search'] = "%$search%";
        }

        if ($teamSize) {
            $sql .= " AND p.team_size = :teamSize";
            $params[':teamSize'] = $teamSize;
        }

        // Tri
        switch ($sort) {
            case 'contributors':
                $sql .= " ORDER BY (SELECT COUNT(*) FROM project_collaborators pc WHERE pc.project_id = p.id) DESC";
                break;
            case 'popular':
                $sql .= " ORDER BY p.views DESC"; // si tu as un champ views
                break;
            default:
                $sql .= " ORDER BY p.created_at DESC";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(fn($data) => new Project($data), $results);
    }



    public function findById(int $id): ?Project
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("
            SELECT p.*, u.firstName AS owner_firstname
            FROM projects p
            JOIN users u ON p.owner_id = u.users_id
            WHERE p.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ? new Project($data) : null;
    }


    public function create(array $data): bool
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare("
            INSERT INTO projects (
                owner_id, title, short_description, description, status, technologies, team_size,
                looking_for, repository_url, demo_url, documentation_url, cover_image
            ) VALUES (
                :owner_id, :title, :short_description, :description, :status, :technologies, :team_size,
                :looking_for, :repository_url, :demo_url, :documentation_url, :cover_image
            )
        ");
        $data['technologies'] = json_encode($data['technologies'] ?? []);
        return $stmt->execute($data);
    }

    public function countAll(): int
    {
        $pdo = Mysql::getInstance()->getPDO();
        return (int) $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
    }
}
