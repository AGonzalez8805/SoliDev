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

                default:
                    throw new \Exception("Cette action n'existe pas : " . $action);
                    break;
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
                // Redirige vers la liste si aucun id
                header("Location: /?controller=blog&action=list");
                exit;
            }

            $id = (int)$_GET['id'];
            $blogRepository = new BlogRepository();
            $blog = $blogRepository->findOneById($id);

            if (!$blog) {
                throw new \Exception("Blog introuvable pour l'id : $id");
            }

            $this->render('blog/show_single', [
                'blog' => $blog,
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
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header("Location: /?controller=auth&action=login");
            exit;
        }

        $this->render('blog/createBlog');
    }

    protected function store(): void
    {
        try {
            // Vérifier que l'utilisateur est connecté
            if (!isset($_SESSION['user_id'])) {
                throw new \Exception("Vous devez être connecté pour créer un article.");
            }

            // Validation des champs obligatoires
            if (empty($_POST['title'])) {
                throw new \Exception("Le titre est obligatoire.");
            }

            if (empty($_POST['category'])) {
                throw new \Exception("La catégorie est obligatoire.");
            }

            if (empty($_POST['excerpt'])) {
                throw new \Exception("Le résumé est obligatoire.");
            }

            if (empty($_POST['content'])) {
                throw new \Exception("Le contenu est obligatoire.");
            }

            // Récupération et nettoyage des données
            $title = trim($_POST['title']);
            $category = trim($_POST['category']);
            $excerpt = trim($_POST['excerpt']);
            $content = $_POST['content']; // Ne pas trim le contenu HTML
            $status = 'published'; // Publié par défaut
            $userId = $_SESSION['user_id']; // Garder en string

            // Gestion de l'upload d'image
            $coverImagePath = null;
            if (!empty($_FILES['cover_image']['name'])) {
                $uploadResult = $this->handleImageUpload($_FILES['cover_image']);
                if ($uploadResult['success']) {
                    $coverImagePath = $uploadResult['path'];
                } else {
                    throw new \Exception($uploadResult['error']);
                }
            }

            // Création de l'objet Blog
            $blog = new Blog();
            $blog->setTitle($title);
            $blog->setCategory($category);
            $blog->setExcerpt($excerpt);
            $blog->setContent($content);
            $blog->setStatus($status);
            $blog->setCoverImage($coverImagePath);
            $blog->setAuthorId($userId);

            // Insertion en base de données
            $blogRepository = new BlogRepository();
            $lastId = $blogRepository->insert($blog);

            if (!$lastId) {
                throw new \Exception("Erreur lors de la création de l'article.");
            }

            // Redirection vers l'article créé
            header("Location: /?controller=blog&action=show&id=" . $lastId);
            exit;
        } catch (\Exception $e) {
            // En cas d'erreur, afficher la page d'erreur
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
        }
    }

    private function handleImageUpload(array $file): array
    {
        // Vérifier qu'il n'y a pas d'erreur d'upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'path' => null,
                'error' => 'Erreur lors du téléchargement du fichier.'
            ];
        }

        // Vérifier la taille (max 2MB)
        $maxSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return [
                'success' => false,
                'path' => null,
                'error' => 'Le fichier est trop volumineux (max 2MB).'
            ];
        }

        // Vérifier le type MIME
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            return [
                'success' => false,
                'path' => null,
                'error' => 'Format de fichier non autorisé. Utilisez JPG, PNG ou WebP.'
            ];
        }

        // Créer le dossier uploads s'il n'existe pas
        $targetDir = APP_ROOT . "/public/uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Générer un nom de fichier unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('blog_', true) . '.' . $extension;
        $targetFile = $targetDir . $fileName;

        // Déplacer le fichier
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return [
                'success' => true,
                'path' => '/uploads/' . $fileName,
                'error' => null
            ];
        }

        return [
            'success' => false,
            'path' => null,
            'error' => 'Erreur lors de la sauvegarde du fichier.'
        ];
    }

    protected function comment(): void
    {
        $this->render('blog/comment');
    }

    protected function preview(): void
    {
        $this->render('blog/preview');
    }
}
