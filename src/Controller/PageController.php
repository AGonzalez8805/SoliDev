<?php

namespace App\Controller;

use App\Config\Mailer;

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
                    case 'sendContact':
                        $this->sendContact();
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

    protected function sendContact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');

            if (!$name || !$email || !$subject || !$message) {
                $_SESSION['error'] = "Tous les champs sont requis.";
                header('Location: /?controller=page&action=contact');
                exit;
            }

            $mailer = new Mailer(true);

            $body = "<p><strong>Nom :</strong> $name</p>
                    <p><strong>Email :</strong> $email</p>
                    <p><strong>Message :</strong><br>$message</p>";

            $sent = $mailer->sendMail(
                $_ENV['MAIL_TO'],   // Destinataire (ton adresse)
                $subject,
                $body,
                $email,             // Reply-to = email de l'utilisateur
                $name
            );

            if ($sent) {
                $_SESSION['success'] = "Merci, votre message a été envoyé !";
            } else {
                $_SESSION['error'] = "Erreur lors de l'envoi du message. Veuillez réessayer.";
            }

            header('Location: /?controller=page&action=contact');
            exit;
        }
    }
}
