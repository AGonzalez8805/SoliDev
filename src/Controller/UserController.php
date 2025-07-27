<?php

namespace App\Controller;

class UserController extends Controller
{
    /**
     * Méthode de routage principale du contrôleur utilisateur.
     * Elle redirige vers la méthode appropriée en fonction de l'action passée en GET.
     */
    public function route(): void
    {
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'dashboard':
                    // Affiche le tableau de bord utilisateur
                    $this->dashboard();
                    break;

                default:
                    // L'action n'est pas reconnue pour ce contrôleur
                    throw new \Exception("Action utilisateur inconnue");
            }
        }
    }

    /**
     * Affiche la vue du tableau de bord pour un utilisateur connecté.
     * Redirige vers la page de login si l'utilisateur n'est pas connecté ou n'a pas le bon rôle.
     */
    public function dashboard(): void
    {
        // Vérifie si l'utilisateur est connecté et a le rôle 'utilisateur'
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'utilisateur') {
            // Redirige vers la page de connexion s'il n'est pas autorisé
            header('Location: /?controller=auth&action=login');
            exit;
        }

        // Affiche la vue du tableau de bord utilisateur
        $this->render('user/dashboard');
    }
}
