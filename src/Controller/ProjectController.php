<?php

namespace App\Controller;

class ProjectController extends Controller
{
    public function route(string $action = 'project'): void
    {
        $this->handleRoute(function () use ($action) {
            switch ($action) {
                case 'project':
                    $this->project();
                    break;
                case 'create':
                    $this->create();
                    break;
                case 'store':
                    $this->store();
                    break;
                case 'view':
                    $this->view();
                    break;
                default:
                    throw new \Exception("Cette action n'existe pas : " . $action);
                    break;
            }
        });
    }

    protected function project(): void
    {
        $status = $_GET['status'] ?? '';
        $tech = $_GET['tech'] ?? '';
        $search = $_GET['q'] ?? '';
        $sort = $_GET['sort'] ?? 'recent';

        $repo = new \App\Repository\ProjectsRepository();
        $projects = $repo->findAll($status, $tech, $search, $sort);

        $metaData = [
            'title' => "Projets - SoliDev",
            'description' => "Découvrez et partagez les projets de la communauté SoliDev. Trouvez des collaborateurs et développez vos idées.",
            'keywords' => "projets, développement, collaboration, SoliDev",
            'pageTitle' => "Projets SoliDev"
        ];


        $this->render('project/project', array_merge($metaData, [
            'projects' => $projects,
            'status' => $status,
            'tech' => $tech,
            'search' => $search,
            'sort' => $sort
        ]));
    }


    protected function create(): void
    {
        $this->render('project/create');
    }
    protected function store(): void
    {
        // Vérification connexion
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous devez être connecté pour créer un projet.";
            header('Location: /?controller=auth&action=login');
            exit;
        }

        $errors = [];

        // ✅ Récupération et nettoyage des données
        $title = trim($_POST['title'] ?? '');
        $short_description = trim($_POST['short_description'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // ENUMs
        $allowedStatuses = ['planning', 'active', 'seeking', 'completed'];
        $status = in_array($_POST['status'] ?? '', $allowedStatuses) ? $_POST['status'] : 'planning';

        $allowedTeamSizes = ['solo', 'small', 'medium', 'large'];
        $team_size = in_array($_POST['team_size'] ?? '', $allowedTeamSizes) ? $_POST['team_size'] : null;

        // Technologies (checkbox + champ texte)
        $techs = $_POST['technologies'] ?? [];
        if (!empty($_POST['other_technologies'])) {
            $other = array_map('trim', explode(',', $_POST['other_technologies']));
            $techs = array_merge($techs, $other);
        }
        $technologies = !empty($techs) ? json_encode($techs, JSON_UNESCAPED_UNICODE) : null;

        $data = [
            'owner_id' => $_SESSION['user_id'],
            'title' => $title,
            'short_description' => $short_description,
            'description' => $description,
            'status' => $status,
            'technologies' => $technologies,
            'team_size' => $team_size,
            'looking_for' => trim($_POST['looking_for'] ?? ''),
            'repository_url' => trim($_POST['repository_url'] ?? ''),
            'demo_url' => trim($_POST['demo_url'] ?? ''),
            'documentation_url' => trim($_POST['documentation_url'] ?? ''),
            'cover_image' => null
        ];

        // ✅ Validation
        if (!$title) $errors[] = "Le titre est obligatoire.";
        if (!$short_description) $errors[] = "La description courte est obligatoire.";
        if (!$description) $errors[] = "La description complète est obligatoire.";

        // ✅ Gestion du fichier image
        if (!empty($_FILES['cover_image']['name'])) {
            $targetDir = APP_ROOT . '/public/uploads/projects/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            $fileName = time() . '_' . basename($_FILES['cover_image']['name']);
            $targetFile = $targetDir . $fileName;

            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetFile)) {
                $data['cover_image'] = '/uploads/projects/' . $fileName;
            } else {
                $errors[] = "Erreur lors de l’envoi de l’image.";
            }
        }

        if (!empty($errors)) {
            $this->render('project/create', ['errors' => $errors]);
            return;
        }

        $repo = new \App\Repository\ProjectsRepository();
        $success = $repo->create($data);

        if ($success) {
            $_SESSION['success'] = "Projet créé avec succès 🎉";
            header('Location: ?controller=project&action=project');
        } else {
            $_SESSION['error'] = "Une erreur est survenue lors de l’enregistrement.";
            $this->render('project/create');
        }
    }

    protected function view(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if (!$id) {
            header('Location: ?controller=project&action=project');
            exit;
        }

        $repo = new \App\Repository\ProjectsRepository();
        $project = $repo->findById($id);

        if (!$project) {
            $_SESSION['error'] = "Projet introuvable.";
            header('Location: ?controller=project&action=project');
            exit;
        }

        $this->render('project/view', ['project' => $project]);
    }
}
