<?php

namespace App\Controller;

class Controller
{
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
    public function route(): void
    {
        $this->handleRoute(function () {
            if (isset($_GET['controller'])) {
                switch ($_GET['controller']) {
                    case 'page':
                        $pageController = new PageController();
                        $pageController->route();
                        break;

                    case 'auth':
                        $controller = new AuthController();
                        $controller->route();
                        break;

                    case 'admin':
                        $controller = new AdminController();
                        $controller->route();
                        break;

                    case 'user':
                        $controller = new UserController();
                        $controller->route();
                        break;
                    
                    case 'forum':
                        $controller = new ForumController();
                        $controller->route();
                        break;

                    default:
                        throw new \Exception("Le contrôleur '{$_GET['controller']}' n'existe pas", 404);
                }
            } else {
                throw new \Exception("Aucun contrôleur détecté");
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
            } else {
                extract($params);
                require_once $filePath;
            }
        } catch (\Exception $e) {
            if ($isRenderingError) {
                echo "<h1>Erreur critique lors du rendu de la vue</h1>";
                echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
                return;
            }
            $isRenderingError = true;
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
            $isRenderingError = false;
        }
    }
}
