<?php

namespace App\Controller;

use App\Config\Mailer;
use App\Repository\UserRepository;

class PageController extends Controller
{
    /**
     * Méthode principale de routage du contrôleur de pages.
     * Elle détermine l'action à exécuter à partir de la query string `?action=...`
     */
    public function route(string $action = 'home'): void
    {
        $this->handleRoute(function () use ($action) {
            switch ($action) {
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

                case 'sendContact':
                    $this->sendContact();
                    break;

                default:
                    // Action non reconnue
                    throw new \Exception("Cette action n'existe pas : " . $action);
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
    public function home(): void
    {
        $userRepo = new UserRepository();
        $stats = $userRepo->getGlobalStats(); // récupère users, blogs, projects, snippets

        $data = [
            'title' => "Accueil - SoliDev",
            'description' => "Bienvenue sur SoliDev, la plateforme collaborative pour développeurs...",
            'keywords' => "développement, code, forum, projets, snippets, blog",
            'stats' => $stats
        ];

        $this->render('page/home', $data, [
            'stats' => $stats
        ]);
    }

    /**
     * Affiche la page de contact
     */
    protected function contact()
    {
        $this->render('page/contact');
    }

    public function sendContact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'name'      => $_POST['name'] ?? '',
                'firstName' => $_POST['firstName'] ?? '',
                'email'     => $_POST['email'] ?? '',
                'phone'     => $_POST['number'] ?? '',
                'subject'   => $_POST['subject'] ?? '',
                'message'   => $_POST['message'] ?? '',
            ];

            // Vérification minimale
            if (!$data['name'] || !$data['firstName'] || !$data['email'] || !$data['subject'] || !$data['message']) {
                $_SESSION['error'] = "Veuillez remplir tous les champs obligatoires.";
                header('Location: ?controller=page&action=contact');
                exit;
            }

            // Utiliser la méthode correcte du Mailer
            $result = $this->mailer->sendContactMail($data);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }

            header('Location: ?controller=page&action=contact');
            exit;
        }
    }
}
