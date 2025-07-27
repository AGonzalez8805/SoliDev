<?php

namespace App\Controller;

class PageController extends Controller
{
    /**
     * Méthode principale de routage du contrôleur de pages.
     * Elle détermine l'action à exécuter à partir de la query string `?action=...`
     */
    public function route(): void
    {
        try {
            // Vérifie si une action est définie dans l'URL
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'about':
                        // Affiche la page "À propos"
                        $this->about();
                        break;

                    case 'home':
                        // Affiche la page d'accueil
                        $this->home();
                        break;

                    case 'contact':
                        // Affiche la page de contact
                        $this->contact();
                        break;

                    default:
                        // Action non reconnue
                        throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
                }
            } else {
                // Aucune action n'a été fournie dans l'URL
                throw new \Exception("Aucune action détectée");
            }
        } catch (\Exception $e) {
            // Affiche une page d'erreur en cas d'exception
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
        }
    }

    /**
     * Affiche la page "À propos"
     */
    protected function about()
    {
        $this->render('page/about');
    }

    /**
     * Affiche la page d'accueil
     */
    protected function home()
    {
        $this->render('page/home');
    }

    /**
     * Affiche la page de contact
     */
    protected function contact()
    {
        $this->render('page/contact');
    }
}
