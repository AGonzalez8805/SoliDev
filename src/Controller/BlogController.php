<?php

namespace App\Controller;

use App\Models\Blog;
use App\Repository\BlogRepository;
use App\Repository\CommentsBlogRepository;
use App\Models\CommentsBlog;

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
                case 'preview':
                    $this->preview();
                    break;
                case 'addComment':
                    $this->addComment();
                    break;

                default:
                    throw new \Exception("Cette action n'existe pas : " . $action);
            }
        });
    }

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

        $commentsRepo = new CommentsBlogRepository();
        foreach ($blogs as $blog) {
            $blog->setCommentsCount($commentsRepo->countByBlogId($blog->getId()));
        }

        $this->render('blog/show', [
            'blogs'      => $blogs,
            'page'       => $page,
            'totalPages' => $totalPages,
            'category'   => $category,
            'search'     => $search,
            'sort'       => $sort,
            'Parsedown'  => new \Parsedown()
        ]);
    }

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

            // ✅ Utiliser le bon repository et le bon model
            $commentRepository = new CommentsBlogRepository();
            $comments = $commentRepository->findByBlogId($id);
            $commentsCount = $commentRepository->countByBlogId($id);

            $this->render('blog/show_single', [
                'blog' => $blog,
                'comments' => $comments,
                'commentsCount' => $commentsCount,
                'Parsedown' => new \Parsedown()
            ]);
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
        }
    }

    protected function createBlog(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /?controller=auth&action=login");
            exit;
        }

        $this->render('blog/createBlog');
    }

    protected function store(): void
    {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new \Exception("Vous devez être connecté pour créer un article.");
            }

            if (empty($_POST['title'])) throw new \Exception("Le titre est obligatoire.");
            if (empty($_POST['category'])) throw new \Exception("La catégorie est obligatoire.");
            if (empty($_POST['excerpt'])) throw new \Exception("Le résumé est obligatoire.");
            if (empty($_POST['content'])) throw new \Exception("Le contenu est obligatoire.");

            $title = trim($_POST['title']);
            $category = trim($_POST['category']);
            $excerpt = trim($_POST['excerpt']);
            $content = $_POST['content'];
            $status = 'published';
            $userId = $_SESSION['user_id'];

            $coverImagePath = null;
            if (!empty($_FILES['cover_image']['name'])) {
                $uploadResult = $this->handleImageUpload($_FILES['cover_image']);
                if ($uploadResult['success']) {
                    $coverImagePath = $uploadResult['path'];
                } else {
                    throw new \Exception($uploadResult['error']);
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

            if (!$lastId) {
                throw new \Exception("Erreur lors de la création de l'article.");
            }

            header("Location: /?controller=blog&action=show&id=" . $lastId);
            exit;
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
        }
    }

    private function handleImageUpload(array $file): array
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'path' => null, 'error' => 'Erreur lors du téléchargement du fichier.'];
        }

        $maxSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'path' => null, 'error' => 'Le fichier est trop volumineux (max 2MB).'];
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            return ['success' => false, 'path' => null, 'error' => 'Format de fichier non autorisé. Utilisez JPG, PNG ou WebP.'];
        }

        $targetDir = APP_ROOT . "/public/uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('blog_', true) . '.' . $extension;
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return ['success' => true, 'path' => '/uploads/' . $fileName, 'error' => null];
        }

        return ['success' => false, 'path' => null, 'error' => 'Erreur lors de la sauvegarde du fichier.'];
    }

    protected function preview(): void
    {
        $this->render('blog/preview');
    }

    protected function addComment(): void
    {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new \Exception("Vous devez être connecté pour commenter.");
            }

            if (empty($_POST['blog_id']) || empty($_POST['content'])) {
                throw new \Exception("Le commentaire ne peut pas être vide.");
            }

            $blogId = (int)$_POST['blog_id'];
            $userId = (int)$_SESSION['user_id'];
            $content = trim($_POST['content']);

            $comment = new CommentsBlog();
            $comment->setBlogId($blogId);
            $comment->setUserId($userId);
            $comment->setContent($content);

            $repo = new CommentsBlogRepository();
            $repo->insert($comment);

            header("Location: /?controller=blog&action=show&id=" . $blogId);
            exit;
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
        }
    }
}
