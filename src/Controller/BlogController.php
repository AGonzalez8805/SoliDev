<?php

namespace App\Controller;

use App\Repository\BlogRepository;

class BlogController extends Controller
{
    public function route(): void
    {
        $this->handleRoute(function () {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'show':
                        $this->show();
                        break;
                    case 'list':
                        $this->list();
                        break;
                    case 'createBlog':
                        $this->createBlog();
                        break;
                    case 'edit':
                        //Appeler méthode edit()
                        break;
                    case 'store':
                        // Appeler méthode store()
                        break;
                    case 'update':
                        //Appeler méthode update()
                        break;
                    case 'delete':
                        //Appeler méthode delete()
                        break;

                    default:
                        throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
                        break;
                }
            }
        });
    }

    protected function show(): void
    {
        try {
            if (!isset($_GET['id'])) {
                $blogRepository = new BlogRepository();
                $blogs = $blogRepository->findAll();

                $this->render('blog/show', [
                    'blogs' => $blogs
                ]);
                return;
            }

            $id = (int)$_GET['id'];
            $blogRepository = new BlogRepository();
            $blog = $blogRepository->findOneById($id);

            if (!$blog) {
                throw new \Exception("Blog introuvable pour l'id : $id");
            }
            $this->render('blog/show', ['blog' => $blog]);
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
        }
    }

    protected function list(): void
    {
        try {
            $blogRepository = new BlogRepository();
            $blogs = $blogRepository->findAll();

            $this->render('blog/list', [
                'blogs' => $blogs
            ]);
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
        }
    }

    protected function createBlog(): void
    {
        $this->render('blog/createBlog');
    }

    protected function store(): void
    {
        try {
            if (empty($_POST['title']) || empty($_POST['description'])) {
                throw new \Exception("Veuillez remplir tous les champs");
            }

            $title = trim($_POST['title']);
            $description = trim($_POST['description']);

            $blogRepository = new BlogRepository();
            $blogRepository->insert($title, $description);

            // Redirige vers la liste après création
            header("Location: /?controller=blog&action=list");
            exit;
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
        }
    }
}
