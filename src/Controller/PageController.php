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
        $this->handleRoute(function () {
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
            }
        });
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
