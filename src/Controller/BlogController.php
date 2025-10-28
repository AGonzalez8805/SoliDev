<?php

namespace App\Controller;

use App\Models\Blog;
use App\Repository\BlogRepository;

class BlogController extends Controller
{
    public function route(string $action = 'show'): void
    {
        $this->handleRoute(function () use ($action) {
            switch ($action) {
                case 'show':
                    $this->show();
                    break;
                case 'list':
                    $this->list();
                    break;
                case 'createBlog':
                    $this->createBlog();
                    break;
                case 'store':
                    $this->store();
                    break;
                case 'comment':
                    $this->comment();
                    break;
                case 'preview':
                    $this->preview();
                    break;
                case 'saveDraft':
                    $this->saveDraft();
                    break;
                case 'getDrafts':
                    $this->getDrafts();
                    break;

                default:
                    throw new \Exception("Cette action n'existe pas : " . $action);
            }
        });
    }

    /** 📰 Page liste des articles */
    protected function list(): void
    {
        $category = $_GET['category'] ?? null;
        $search   = $_GET['q'] ?? null;
        $sort     = $_GET['sort'] ?? 'recent';

        $limit = 5;
        $page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $blogRepository = new BlogRepository();
        $blogs = $blogRepository->findFilteredPaginated($category, $search, $sort, $limit, $offset);

        $totalBlogs = $blogRepository->countFiltered($category, $search);
        $totalPages = (int) ceil($totalBlogs / $limit);

        // ✅ Données SEO
        $metaData = [
            'title' => "Blog des Développeurs - SoliDev",
            'description' => "Lisez et partagez des articles techniques, astuces et retours d'expérience entre développeurs sur SoliDev.",
            'keywords' => "blog développeur, articles, programmation, code, astuces, SoliDev",
            'pageTitle' => "Blog - Accueil"
        ];

        $this->render('blog/show', array_merge($metaData, [
            'blogs'      => $blogs,
            'page'       => $page,
            'totalPages' => $totalPages,
            'category'   => $category,
            'search'     => $search,
            'sort'       => $sort,
            'Parsedown'  => new \Parsedown()
        ]));
    }

    /** 🧾 Page article individuel */
    protected function show(): void
    {
        try {
            if (!isset($_GET['id'])) {
                header("Location: /?controller=blog&action=list");
                exit;
            }

            $id = (int)$_GET['id'];
            $blogRepository = new BlogRepository();
            $blog = $blogRepository->findOneById($id);

            if (!$blog) {
                throw new \Exception("Blog introuvable pour l'id : $id");
            }

            // ✅ Données SEO dynamiques selon l’article
            $metaData = [
                'title' => $blog->getTitle() . " - SoliDev",
                'description' => $blog->getExcerpt() ?: "Découvrez un nouvel article technique sur SoliDev.",
                'keywords' => "développement, programmation, blog, " . strtolower($blog->getCategory()) . ", SoliDev",
                'pageTitle' => $blog->getTitle()
            ];

            $this->render('blog/show_single', array_merge($metaData, [
                'blog' => $blog,
                'Parsedown' => new \Parsedown()
            ]));
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
        }
    }

    protected function createBlog(): void
    {
        $metaData = [
            'title' => "Créer un article - SoliDev",
            'description' => "Rédigez et publiez vos articles techniques sur SoliDev pour partager vos connaissances avec la communauté.",
            'keywords' => "rédaction, blog développeur, création article, SoliDev",
            'pageTitle' => "Créer un article"
        ];

        $this->render('blog/createBlog', $metaData);
    }

    protected function store(): void
    {
        try {
            if (empty($_POST['title']) || empty($_POST['category']) || empty($_POST['content']) || empty($_POST['excerpt'])) {
                throw new \Exception("Veuillez remplir tous les champs obligatoires (*)");
            }

            $title = trim($_POST['title']);
            $category = $_POST['category'];
            $excerpt = trim($_POST['excerpt']);
            $content = trim($_POST['content']);
            $status = $_POST['status'] ?? 'draft';
            $userId = $_SESSION['user_id'] ?? null;

            $coverImagePath = null;
            if (!empty($_FILES['cover_image']['name'])) {
                $maxSize = 2 * 1024 * 1024;
                if ($_FILES['cover_image']['size'] > $maxSize) {
                    throw new \Exception("Le fichier est trop volumineux (max 2MB).");
                }

                $targetDir = APP_ROOT . "/public/uploads/";
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

                $fileName = time() . "_" . basename($_FILES['cover_image']['name']);
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetFile)) {
                    $coverImagePath = "/uploads/" . $fileName;
                }
            }

            $blog = new Blog();
            $blog->setTitle($title);
            $blog->setCategory($category);
            $blog->setExcerpt($excerpt);
            $blog->setContent($content);
            $blog->setStatus($status);
            $blog->setCoverImage($coverImagePath);
            $blog->setAuthorId($userId);

            $blogRepository = new BlogRepository();
            $lastId = $blogRepository->insert($blog);

            header("Location: /?controller=blog&action=show&id=" . $lastId);
            exit;
        } catch (\Exception $e) {
            $this->render('errors/default', ['errors' => $e->getMessage()]);
        }
    }

    protected function comment(): void
    {
        $this->render('blog/comment');
    }

    protected function preview(): void
    {
        $this->render('blog/preview');
    }

    public function saveDraft()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $_SESSION['user_id'];

        $blog = new Blog();
        $blog->setTitle($data['title'] ?? '');
        $blog->setCategory($data['category'] ?? 'other');
        $blog->setExcerpt($data['excerpt'] ?? '');
        $blog->setContent($data['content'] ?? '');
        $blog->setStatus('draft');
        $blog->setAuthorId($userId);

        $blogRepo = new \App\Repository\BlogRepository();
        $id = $blogRepo->insert($blog);

        echo json_encode(['success' => true, 'id' => $id]);
    }

    protected function getDrafts(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $userId = $_SESSION['user_id'] ?? null;
            if (!$userId) {
                echo json_encode(['success' => false, 'error' => 'Utilisateur non connecté']);
                return;
            }

            $blogRepository = new BlogRepository();
            $drafts = $blogRepository->findDraftsByUser($userId);

            echo json_encode(['success' => true, 'drafts' => $drafts]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}
