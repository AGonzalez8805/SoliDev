<?php

namespace App\Controller;

class Controller
{
    /**
     * Méthode principale de routage.
     * Elle redirige vers le bon contrôleur selon la valeur de $_GET['controller'].
     */
    public function route(): void
    {
        try {
            if (isset($_GET['controller'])) {
                switch ($_GET['controller']) {

                    case 'page':
                        // Charger le contrôleur de la page (page statique, page d'accueil, etc.)
                        $pageController = new PageController();
                        $pageController->route();
                        break;

                    case 'blog':
                        // Charger le contrôleur lié aux articles / blog
                        $controller = new BlogController();
                        $controller->route();
                        break;

                    case 'auth':
                        // Charger le contrôleur d'authentification (login, logout, register)
                        $controller = new AuthController();
                        $controller->route();
                        break;

                    case 'admin':
                        // Charger le contrôleur admin (zone protégée)
                        $controller = new AdminController();
                        $controller->route();
                        break;

                    case 'user':
                        // Charger le contrôleur utilisateur (profil, actions utilisateur)
                        $controller = new UserController();
                        $controller->route();
                        break;

                    default:
                        // Contrôleur non reconnu
                        throw new \Exception("Le contrôleur n'existe pas");
                }
            } else {
                // Aucune valeur GET['controller'] : on affiche la page d'accueil par défaut
                $pageController = new PageController();
                $pageController->home();
            }
        } catch (\Exception $e) {
            // En cas d'erreur, afficher la vue d'erreur générique
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
        }
    }

    /**
     * Méthode pour afficher une vue.
     */
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
                // Si on est déjà en train de rendre une erreur, afficher un message brut pour éviter la récursion
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
