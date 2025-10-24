<?php

namespace App\Controller;

use App\Repository\UserRepository;

// Déclaration de la classe AdminController qui hérite de la classe Controller
class AdminController extends Controller
{
    /**
     * Routeur de l'AdminController.
     * Il regarde si un paramètre "action" est présent dans l'URL, et appelle la méthode correspondante.
     */
    public function route(string $action = 'dashboard'): void
    {
        $this->handleRoute(function () use ($action) {
            switch ($action) {
                case 'dashboard': // Si action = "dashboard"
                    $this->dashboard(); // Appelle la méthode dashboard()
                    break;
                default:
                    // Si l'action demandée n'existe pas, lève une exception
                    throw new \Exception("Action admin inconnue");
            }
        });
    }

    /**
     * Affiche le tableau de bord admin (dashboard).
     * Vérifie d'abord que l'utilisateur est bien connecté et qu'il a le rôle "admin".
     * Sinon, redirige vers la page de connexion.
     */
    public function dashboard(): void
    {
        // Vérifie que l'utilisateur est connecté ET que son rôle est "admin"
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?controller=auth&action=login');
            exit;
        }

        $userRepo = new UserRepository();

        $stats = $userRepo->getGlobalStats(); // Stats globales
        $users = $userRepo->findAllExcept($_SESSION['user_id']); // Exclut l'utilisateur connecté
        $monthlyUsers = $userRepo->getMonthlyRegistrations(); // Tableau [12, 15, 7,...]
        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $this->render('admin/dashboard', [
            'stats' => $stats,
            'users' => $users,
            'monthlyUsers' => $monthlyUsers,
            'monthlyLabels' => $monthlyLabels
        ]);
    }
}
