<?php

namespace App\Controller;

use App\Repository\TopicRepository;
use App\Repository\UserRepository;

class ForumController extends Controller
{
    private TopicRepository $topicRepository;

    public function __construct()
    {
        // Instancie ton UserRepository MySQL
        $mysqlUserRepo = new UserRepository();

        // Passe l'instance à TopicRepository pour récupérer les noms depuis MySQL
        $this->topicRepository = new TopicRepository($mysqlUserRepo);
    }

    /** Route principal */
    public function route(): void
    {
        $this->handleRoute(function () {
            $action = $_GET['action'] ?? 'forum';
            switch ($action) {
                case 'forum':
                    $this->forum();
                    break;
                case 'createPost':
                    $this->createPost();
                    break;
                case 'save_post':
                    $this->savePost();
                    break;
                case 'category':
                    $this->category();
                    break;
                default:
                    throw new \Exception("Cette action n'existe pas : " . $action);
            }
        });
    }

    public function forum(): void
    {
        $categories = $this->buildCategoriesWithStats();
        $onlineUsers = $this->getOnlineUsersData();

        // Vérifie si une catégorie est demandée
        $selectedCategory = $_GET['category'] ?? null;

        if ($selectedCategory && $this->isValidCategory($selectedCategory)) {
            // On récupère seulement les topics de cette catégorie
            $recentTopics = $this->topicRepository->getTopicsByCategory($selectedCategory, 20, 0);
        } else {
            // Sinon on récupère tous les topics
            $recentTopics = $this->topicRepository->findAll();
        }


        $this->render('forum/forum', [
            'categories' => $categories,
            'onlineUsers' => $onlineUsers,
            'recentTopics' => array_slice($recentTopics, 0, 5),
            'pageTitle' => 'Forum - Accueil',
            'selectedCategory' => $selectedCategory
        ]);
    }

    public function createPost(): void
    {
        $this->requireAuthentication();

        $this->render('forum/createPost', [
            'categories' => $this->getAvailableCategories(),
            'pageTitle' => 'Créer un nouveau sujet',
            'errors' => $_SESSION['form_errors'] ?? [],
            'oldInput' => $_SESSION['old_input'] ?? [],
            'successMessage' => $_SESSION['success_message'] ?? null
        ]);

        $this->clearFormSession();
    }

    public function savePost(): void
    {
        $this->requireAuthentication();
        $this->requirePostMethod();

        $validatedData = $this->validatePostData($_POST);
        $validatedData['author_id'] = $_SESSION['user_id'];

        if (!empty($_SESSION['name']) && !empty($_SESSION['firstName'])) {
            $validatedData['author_name'] = $_SESSION['firstName'] . ' ' . $_SESSION['name'];
        } elseif (!empty($_SESSION['name'])) {
            $validatedData['author_name'] = $_SESSION['name'];
        } elseif (!empty($_SESSION['username'])) {
            $validatedData['author_name'] = $_SESSION['username'];
        } else {
            $validatedData['author_name'] = 'Anonyme';
        }

        $topicId = $this->topicRepository->createTopic($validatedData);

        $_SESSION['success_message'] = 'Votre sujet a été créé avec succès !';
        header('Location: /?controller=forum&action=forum');
        exit;
    }

    public function topic(): void
    {
        $topicId = $_GET['id'] ?? null;
        if (!$topicId) throw new \Exception('Topic non spécifié', 404);

        $topic = $this->topicRepository->getTopicById($topicId);
        if (!$topic) throw new \Exception('Topic introuvable', 404);

        $this->incrementTopicViews($topicId);

        $this->render('forum/topic', [
            'topic' => $topic,
            'pageTitle' => $topic->getTitle(),
            'canReply' => $this->isAuthenticated(),
            'isAuthor' => $this->isTopicAuthor($topic),
            'breadcrumb' => $this->buildTopicBreadcrumb($topic)
        ]);
    }

    public function category(): void
    {
        $categorySlug = $_GET['category'] ?? null;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;

        if (!$categorySlug || !$this->isValidCategory($categorySlug)) {
            throw new \Exception('Catégorie invalide', 404);
        }

        $offset = ($page - 1) * $perPage;
        $categoryTopics = $this->topicRepository->getTopicsByCategory($categorySlug, $perPage, $offset);
        $totalTopics = $this->topicRepository->countTopicsByCategory($categorySlug);
        $totalPages = ceil($totalTopics / $perPage);
        $categoryInfo = $this->getCategoryInfo($categorySlug);

        $this->render('forum/category', [
            'topics' => $categoryTopics,
            'category' => $categorySlug,
            'categoryInfo' => $categoryInfo,
            'pageTitle' => "Catégorie : " . $categoryInfo['title'],
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'perPage' => $perPage,
                'totalItems' => $totalTopics
            ],
            'breadcrumb' => $this->buildCategoryBreadcrumb($categoryInfo)
        ]);
    }

    // ==================== MÉTHODES PRIVÉES ====================

    /** Construit les catégories avec statistiques */
    private function buildCategoriesWithStats(): array
    {
        $categoriesConfig = [
            'web' => ['title' => 'Développement Web', 'description' => 'HTML, CSS, JS, frameworks...', 'icon' => 'fas fa-globe', 'color' => '#3498db'],
            'mobile' => ['title' => 'Développement Mobile', 'description' => 'iOS, Android, Flutter, etc.', 'icon' => 'fas fa-mobile-alt', 'color' => '#9b59b6'],
            'backend' => ['title' => 'Backend & Bases de données', 'description' => 'PHP, Node.js, MongoDB...', 'icon' => 'fas fa-server', 'color' => '#e74c3c'],
            'devops' => ['title' => 'DevOps & Cloud', 'description' => 'Docker, Kubernetes, AWS...', 'icon' => 'fas fa-cloud', 'color' => '#2ecc71'],
            'help' => ['title' => 'Aide & Support', 'description' => 'Questions et support technique', 'icon' => 'fas fa-question-circle', 'color' => '#f39c12'],
            'general' => ['title' => 'Discussion générale', 'description' => 'Sujets divers et actualités', 'icon' => 'fas fa-users', 'color' => '#95a5a6'],
        ];

        $stats = [];
        foreach ($categoriesConfig as $slug => $cat) {
            $stats[$slug]['topics'] = $this->topicRepository->countTopicsByCategory($slug);
            $stats[$slug]['messages'] = 0; // À remplacer par le nombre de réponses si collection messages
        }

        foreach ($categoriesConfig as $key => &$category) {
            $category['topics'] = $stats[$key]['topics'] ?? 0;
            $category['messages'] = $stats[$key]['messages'] ?? 0;
            $category['slug'] = $key;
        }

        return $categoriesConfig;
    }

    /** Utilisateurs en ligne */
    private function getOnlineUsersData(): array
    {
        return [
            ['username' => 'JohnDev', 'avatar' => null, 'status' => 'online'],
            ['username' => 'MariaL', 'avatar' => null, 'status' => 'online'],
            ['username' => 'TechCoder', 'avatar' => null, 'status' => 'away'],
        ];
    }

    /** Validation et sécurisation des données */
    private function validatePostData(array $data): array
    {
        $errors = [];
        $title = trim($data['title'] ?? '');
        if (empty($title)) $errors['title'] = 'Le titre est obligatoire';
        elseif (strlen($title) < 5) $errors['title'] = 'Le titre doit contenir au moins 5 caractères';
        elseif (strlen($title) > 200) $errors['title'] = 'Le titre ne peut pas dépasser 200 caractères';

        $content = trim($data['content'] ?? $data['message'] ?? '');
        if (empty($content)) $errors['content'] = 'Le message est obligatoire';
        elseif (strlen($content) < 10) $errors['content'] = 'Le message doit contenir au moins 10 caractères';
        elseif (strlen($content) > 10000) $errors['content'] = 'Le message ne peut pas dépasser 10 000 caractères';

        $category = $data['category'] ?? '';
        if (!$this->isValidCategory($category)) $errors['category'] = 'Catégorie invalide';

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: /?controller=forum&action=createPost');
            exit;
        }

        return [
            'title' => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            'content' => htmlspecialchars($content, ENT_QUOTES, 'UTF-8'),
            'category' => $category,
            'tags' => $this->processTags($data['tags'] ?? ''),
            'topic_type' => $data['topic_type'] ?? 'discussion'
        ];
    }

    /** Traitement des tags */
    private function processTags(string $tagsString): array
    {
        if (empty(trim($tagsString))) return [];
        $tags = array_filter(array_unique(array_map('trim', explode(',', $tagsString))), fn($t) => strlen($t) > 0 && strlen($t) <= 30);
        return array_slice($tags, 0, 5);
    }

    /** Vérifie si l'utilisateur est l'auteur du topic */
    private function isTopicAuthor($topic): bool
    {
        return $this->isAuthenticated() && ($_SESSION['user_id'] ?? 0) == $topic->getAuthorId();
    }

    /** Construit le breadcrumb pour un topic */
    private function buildTopicBreadcrumb($topic): array
    {
        $categoryInfo = $this->getCategoryInfo($topic->getCategory());
        return [
            ['title' => 'Forum', 'url' => '/?controller=forum&action=forum'],
            ['title' => $categoryInfo['title'], 'url' => '/?controller=forum&action=category&category=' . $topic->getCategory()],
            ['title' => $topic->getTitle(), 'url' => null]
        ];
    }

    /** Construit le breadcrumb pour une catégorie */
    private function buildCategoryBreadcrumb(array $categoryInfo): array
    {
        return [
            ['title' => 'Forum', 'url' => '/?controller=forum&action=forum'],
            ['title' => $categoryInfo['title'], 'url' => null]
        ];
    }

    /** Récupère les informations d'une catégorie */
    private function getCategoryInfo(string $categorySlug): array
    {
        $categories = $this->buildCategoriesWithStats();
        return $categories[$categorySlug] ?? [
            'title' => 'Catégorie inconnue',
            'description' => '',
            'icon' => 'fas fa-folder',
            'color' => '#95a5a6'
        ];
    }

    /** Vérifie si une catégorie est valide */
    private function isValidCategory(string $category): bool
    {
        return in_array($category, ['web', 'mobile', 'backend', 'devops', 'help', 'general']);
    }

    /** Récupère les catégories pour les formulaires */
    private function getAvailableCategories(): array
    {
        return [
            'web' => 'Développement Web',
            'mobile' => 'Développement Mobile',
            'backend' => 'Backend & Bases de données',
            'devops' => 'DevOps & Cloud',
            'help' => 'Aide & Support',
            'general' => 'Discussion générale'
        ];
    }

    /** Vérifie l'authentification */
    private function requireAuthentication(): void
    {
        if (!$this->isAuthenticated()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            throw new \Exception('Vous devez être connecté pour effectuer cette action', 401);
        }
    }

    /** Vérifie si l'utilisateur est connecté */
    private function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id']);
    }

    /** Vérifie la méthode POST */
    private function requirePostMethod(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new \Exception('Méthode non autorisée', 405);
    }

    /** Nettoie les données de session après affichage */
    private function clearFormSession(): void
    {
        unset($_SESSION['form_errors'], $_SESSION['old_input'], $_SESSION['success_message']);
    }

    /** Incrémente le compteur de vues d'un topic (à implémenter selon tes besoins) */
    private function incrementTopicViews(string $topicId): void
    {
        // À implémenter si tu souhaites suivre les vues
    }
}
