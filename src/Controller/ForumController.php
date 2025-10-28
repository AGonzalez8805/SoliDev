<?php

namespace App\Controller;

use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Models\Comment;

class ForumController extends Controller
{
    private TopicRepository $topicRepository;
    private CommentRepository $commentRepository;

    public function __construct()
    {
        $mysqlUserRepo = new UserRepository();
        $this->topicRepository = new TopicRepository($mysqlUserRepo);
        $this->commentRepository = new CommentRepository($mysqlUserRepo);
    }

    /** Route principal */
    public function route(string $action = 'forum'): void
    {
        $this->handleRoute(function () use ($action) {
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
                case 'topic':
                    $this->topic();
                    break;
                case 'addComment':
                    $this->addComment();
                    break;
                case 'deleteComment':
                    $this->deleteComment();
                    break;
                default:
                    throw new \Exception("Cette action n'existe pas : " . $action);
            }
        });
    }

    public function forum(): void
    {
        $categories = $this->buildCategoriesWithStats();
        $selectedCategory = $_GET['category'] ?? null;

        if ($selectedCategory && $this->isValidCategory($selectedCategory)) {
            $recentTopics = $this->topicRepository->getTopicsByCategory($selectedCategory, 20, 0);
        } else {
            $recentTopics = $this->topicRepository->findAll();
        }

        // Ajouter le nombre de commentaires à chaque topic
        foreach ($recentTopics as $topic) {
            $commentCount = $this->commentRepository->countByTopicId($topic->getId());
            $topic->setCommentCount($commentCount);
        }

        $data = [
            'title' => "Forum - SoliDev",
            'description' => "Participez au forum SoliDev : échangez sur le développement web, mobile, backend, cloud et plus encore.",
            'keywords' => "forum développeurs, entraide, questions, programmation, SoliDev",
            'pageTitle' => "Forum - Accueil",
            'categories' => $categories,
            'recentTopics' => array_slice($recentTopics, 0, 5),
            'selectedCategory' => $selectedCategory
        ];

        $this->render('forum/forum', $data, [
            'categories' => $categories,
            'recentTopics' => array_slice($recentTopics, 0, 5),
            'pageTitle' => 'Forum - Accueil',
            'selectedCategory' => $selectedCategory
        ]);
    }

    public function createPost(): void
    {
        $this->requireAuthentication();

        $data = [
            'title' => "Créer un sujet - Forum SoliDev",
            'description' => "Créez un nouveau sujet sur le forum SoliDev et échangez avec d'autres développeurs.",
            'keywords' => "forum développeurs, créer sujet, entraide, SoliDev",
            'pageTitle' => "Créer un sujet",
            'categories' => $this->getAvailableCategories(),
            'errors' => $_SESSION['form_errors'] ?? [],
            'oldInput' => $_SESSION['old_input'] ?? [],
            'successMessage' => $_SESSION['success_message'] ?? null
        ];

        $this->render('forum/createPost', $data, [
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

        // Récupérer les commentaires
        $comments = $this->commentRepository->getCommentsByTopicId($topicId);

        $data = [
            'title' => $topic->getTitle() . " - Forum SoliDev",
            'description' => substr(strip_tags($topic->getContent()), 0, 160),
            'keywords' => "forum, " . $topic->getCategory() . ", discussion, développeur, SoliDev",
            'pageTitle' => $topic->getTitle(),
            'topic' => $topic,
            'comments' => $comments,
            'commentCount' => count($comments),
            'canReply' => $this->isAuthenticated(),
            'isAuthor' => $this->isTopicAuthor($topic),
            'breadcrumb' => $this->buildTopicBreadcrumb($topic),
            'successMessage' => $_SESSION['success_message'] ?? null,
            'errors' => $_SESSION['form_errors'] ?? []
        ];

        $this->render('forum/topic', $data, [
            'topic' => $topic,
            'comments' => $comments,
            'commentCount' => count($comments),
            'pageTitle' => $topic->getTitle(),
            'canReply' => $this->isAuthenticated(),
            'isAuthor' => $this->isTopicAuthor($topic),
            'breadcrumb' => $this->buildTopicBreadcrumb($topic),
            'successMessage' => $_SESSION['success_message'] ?? null,
            'errors' => $_SESSION['form_errors'] ?? []
        ]);

        $this->clearFormSession();
    }

    public function addComment(): void
    {
        $this->requireAuthentication();
        $this->requirePostMethod();

        $topicId = $_POST['topic_id'] ?? null;
        $content = trim($_POST['content'] ?? '');

        $errors = [];

        if (!$topicId) {
            $errors['topic'] = 'Topic non spécifié';
        }

        if (empty($content)) {
            $errors['content'] = 'Le commentaire ne peut pas être vide';
        } elseif (strlen($content) < 3) {
            $errors['content'] = 'Le commentaire doit contenir au moins 3 caractères';
        } elseif (strlen($content) > 5000) {
            $errors['content'] = 'Le commentaire ne peut pas dépasser 5000 caractères';
        }

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            header('Location: /?controller=forum&action=topic&id=' . $topicId);
            exit;
        }

        // Déterminer le nom de l'auteur
        $authorName = 'Anonyme';
        if (!empty($_SESSION['firstName']) && !empty($_SESSION['name'])) {
            $authorName = $_SESSION['firstName'] . ' ' . $_SESSION['name'];
        } elseif (!empty($_SESSION['name'])) {
            $authorName = $_SESSION['name'];
        } elseif (!empty($_SESSION['username'])) {
            $authorName = $_SESSION['username'];
        }

        $comment = new Comment(
            id: null,
            topicId: $topicId,
            content: htmlspecialchars($content, ENT_QUOTES, 'UTF-8'),
            authorId: $_SESSION['user_id'],
            authorName: $authorName,
            createdAt: new \DateTime()
        );

        $this->commentRepository->create($comment);

        $_SESSION['success_message'] = 'Votre commentaire a été ajouté avec succès !';
        header('Location: /?controller=forum&action=topic&id=' . $topicId);
        exit;
    }

    public function deleteComment(): void
    {
        $this->requireAuthentication();

        $commentId = $_GET['comment_id'] ?? null;
        $topicId = $_GET['topic_id'] ?? null;

        if (!$commentId || !$topicId) {
            throw new \Exception('Paramètres manquants', 400);
        }

        $comment = $this->commentRepository->findById($commentId);

        if (!$comment) {
            throw new \Exception('Commentaire introuvable', 404);
        }

        // Vérifier que l'utilisateur est l'auteur du commentaire
        if ($comment->getAuthorId() !== $_SESSION['user_id']) {
            throw new \Exception('Action non autorisée', 403);
        }

        $this->commentRepository->delete($commentId);

        $_SESSION['success_message'] = 'Commentaire supprimé avec succès';
        header('Location: /?controller=forum&action=topic&id=' . $topicId);
        exit;
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

        // Ajouter le nombre de commentaires
        foreach ($categoryTopics as $topic) {
            $commentCount = $this->commentRepository->countByTopicId($topic->getId());
            $topic->commentCount = $commentCount;
        }

        $data = [
            'title' => "Forum " . $categoryInfo['title'] . " - SoliDev",
            'description' => $categoryInfo['description'] . " | Rejoignez la discussion sur SoliDev.",
            'keywords' => strtolower($categoryInfo['title']) . ", forum développeurs, SoliDev",
            'pageTitle' => "Catégorie : " . $categoryInfo['title'],
            'topics' => $categoryTopics,
            'category' => $categorySlug,
            'categoryInfo' => $categoryInfo,
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'perPage' => $perPage,
                'totalItems' => $totalTopics
            ],
            'breadcrumb' => $this->buildCategoryBreadcrumb($categoryInfo)
        ];


        $this->render('forum/category', $data, [
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
            $stats[$slug]['messages'] = 0;
        }

        foreach ($categoriesConfig as $key => &$category) {
            $category['topics'] = $stats[$key]['topics'] ?? 0;
            $category['messages'] = $stats[$key]['messages'] ?? 0;
            $category['slug'] = $key;
        }

        return $categoriesConfig;
    }

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

    private function processTags(string $tagsString): array
    {
        if (empty(trim($tagsString))) return [];
        $tags = array_filter(array_unique(array_map('trim', explode(',', $tagsString))), fn($t) => strlen($t) > 0 && strlen($t) <= 30);
        return array_slice($tags, 0, 5);
    }

    private function isTopicAuthor($topic): bool
    {
        return $this->isAuthenticated() && ($_SESSION['user_id'] ?? 0) == $topic->getAuthorId();
    }

    private function buildTopicBreadcrumb($topic): array
    {
        $categoryInfo = $this->getCategoryInfo($topic->getCategory());
        return [
            ['title' => 'Forum', 'url' => '/?controller=forum&action=forum'],
            ['title' => $categoryInfo['title'], 'url' => '/?controller=forum&action=category&category=' . $topic->getCategory()],
            ['title' => $topic->getTitle(), 'url' => null]
        ];
    }

    private function buildCategoryBreadcrumb(array $categoryInfo): array
    {
        return [
            ['title' => 'Forum', 'url' => '/?controller=forum&action=forum'],
            ['title' => $categoryInfo['title'], 'url' => null]
        ];
    }

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

    private function isValidCategory(string $category): bool
    {
        return in_array($category, ['web', 'mobile', 'backend', 'devops', 'help', 'general']);
    }

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

    private function requireAuthentication(): void
    {
        if (!$this->isAuthenticated()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $this->render('errors/notLogin', [
                'pageTitle' => 'Connexion requise',
                'message' => 'Vous devez être connecté'
            ]);
            exit;
        }
    }

    private function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id']);
    }

    private function requirePostMethod(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new \Exception('Méthode non autorisée', 405);
    }

    private function clearFormSession(): void
    {
        unset($_SESSION['form_errors'], $_SESSION['old_input'], $_SESSION['success_message']);
    }

    private function incrementTopicViews(string $topicId): void
    {
        // À implémenter si souhaité
    }
}
