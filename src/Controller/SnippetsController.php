<?php

namespace App\Controller;

use App\Repository\SnippetRepository;
use App\Models\Snippet;

class SnippetsController extends Controller
{
    public function route(string $action = 'snippets'): void
    {
        $this->handleRoute(function () use ($action) {
            switch ($action) {
                case 'snippets':
                    $this->snippets();
                    break;
                case 'createSnippet':
                    $this->createSnippet();
                    break;
                case 'store':
                    $this->store();
                    break;
                case 'show':
                    $this->show();
                    break;
                default:
                    throw new \Exception("Cette action n'existe pas: " . $action);
            }
        });
    }

    public function snippets(): void
    {
        $repo = new SnippetRepository();
        $snippets = $repo->findFilteredPaginated(
            $_GET['category'] ?? null,
            $_GET['q'] ?? null,
            $_GET['sort'] ?? 'recent',
            20,
            0
        );

        $this->render('snippets/snippets', ['snippets' => $snippets]);
    }

    public function createSnippet(): void
    {
        $this->render('snippets/createSnippet');
    }

    public function store(): void
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous devez être connecté pour créer un snippet.";
            header('Location: /?controller=auth&action=login');
            exit;
        }

        // Vérification de la soumission
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?controller=snippets&action=createSnippet');
            exit;
        }

        try {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $language = trim($_POST['language'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $code = trim($_POST['code'] ?? '');

            // Validation côté serveur
            if (empty($title) || empty($description) || empty($language) || empty($category) || empty($code)) {
                throw new \Exception("Tous les champs obligatoires doivent être remplis.");
            }

            $snippet = new Snippet();
            $snippet->setAuthorId($_SESSION['user_id']);
            $snippet->setTitle($title);
            $snippet->setDescription($description);
            $snippet->setLanguage($language);
            $snippet->setCategory($category);
            $snippet->setCode($code);
            $snippet->setUsageExample($_POST['usage'] ?? null);
            $snippet->setTags($_POST['tags'] ?? null);
            $snippet->setVisibility($_POST['visibility'] ?? 'public');
            $snippet->setAllowComments(isset($_POST['allow_comments']) ? 1 : 0);
            $snippet->setAllowFork(isset($_POST['allow_fork']) ? 1 : 0);


            $repo = new SnippetRepository();
            $repo->insert($snippet);

            $_SESSION['success'] = "Snippet créé avec succès !";
            header('Location: /?controller=snippets&action=snippets');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = "Erreur lors de la création du snippet : " . $e->getMessage();
            header('Location: /?controller=snippets&action=createSnippet');
            exit;
        }
    }

    public function show(): void
    {
        // Vérifie qu’un ID est fourni
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            $_SESSION['error'] = "ID de snippet invalide.";
            header('Location: /?controller=snippets&action=snippets');
            exit;
        }

        $repo = new SnippetRepository();
        $snippet = $repo->findById((int)$id);

        if (!$snippet) {
            $_SESSION['error'] = "Snippet introuvable.";
            header('Location: /?controller=snippets&action=snippets');
            exit;
        }

        // Incrémente le compteur de vues
        $repo->incrementViews((int)$id);

        // Affiche la vue
        $this->render('snippets/show', ['snippet' => $snippet]);
    }
}
