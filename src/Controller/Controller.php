<?php

namespace App\Controller;

use App\Config\Mailer;

class Controller
{
    protected Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /* Try/catch commun pour tous les contrôleurs */
    protected function handleRoute(callable $callback): void
    {
        try {
            $callback();
        } catch (\Exception $e) {
            if (
                isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            ) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            } else {
                $this->render('errors/default', [
                    'errors' => $e->getMessage()
                ]);
            }
        }
    }

    /* Routeur principal (dispatch vers les autres contrôleurs) */
    public function route(string $action = 'home'): void
    {
        $this->handleRoute(function () {
            // 🟢 Valeurs par défaut
            $controllerName = $_GET['controller'] ?? 'page';
            $action = $_GET['action'] ?? 'home';

            // ✅ Plus besoin de "if (isset($_GET['controller']))"
            switch ($controllerName) {
                case 'page':
                    $controller = new PageController($this->mailer);
                    $controller->route($action);
                    break;

                case 'auth':
                    $controller = new AuthController($this->mailer);
                    $controller->route($action);
                    break;

                case 'admin':
                    $controller = new AdminController($this->mailer);
                    $controller->route($action);
                    break;

                case 'user':
                    $controller = new UserController($this->mailer);
                    $controller->route($action);
                    break;

                case 'forum':
                    $controller = new ForumController($this->mailer);
                    $controller->route($action);
                    break;

                case 'blog':
                    $controller = new BlogController($this->mailer);
                    $controller->route($action);
                    break;

                default:
                    throw new \Exception("Le contrôleur '{$controllerName}' n'existe pas", 404);
            }
        });
    }

    /* Méthode pour afficher une vue */
    protected function render(string $path, array $params = []): void
    {
        static $isRenderingError = false;
        $filePath = APP_ROOT . '/views/' . $path . '.php';

        try {
            if (!file_exists($filePath)) {
                throw new \Exception("Fichier non trouvé : " . $filePath);
            }

            extract($params);
            require $filePath;
        } catch (\Exception $e) {
            if ($isRenderingError) {
                echo "<h1>Erreur critique lors du rendu de la vue</h1>";
                echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
                return;
            }

            $isRenderingError = true;
            $this->render('errors/default', ['errors' => $e->getMessage()]);
            $isRenderingError = false;
        }
    }
}
