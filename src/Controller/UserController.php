<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Models\User;

class UserController extends Controller
{
    public function route(): void
    {
        $this->handleRoute(function () {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'dashboard':
                        $this->dashboard();
                        break;
                    case 'uploadPhoto':
                        $this->uploadPhoto();
                        break;

                    default:
                        throw new \Exception("Action utilisateur inconnue");
                }
            }
        });
    }

    public function dashboard(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'utilisateur') {
            header('Location: /?controller=auth&action=login');
            exit;
        }

        $userRepo = new UserRepository();
        $userData = $userRepo->findById($_SESSION['user_id']);

        if (!$userData) {
            session_destroy();
            header('Location: /?controller=auth&action=login');
            exit;
        }

        // Hydratation du modèle
        $user = (new User())
            ->setId($userData['users_id'])
            ->setName($userData['name'])
            ->setFirstName($userData['firstName'])
            ->setEmail($userData['email'])
            ->setRole($userData['role'])
            ->setPhoto($userData['photo'] ?? null);

        // 🔹 Exemple de stats dynamiques
        $stats = [
            'messages' => 12, // $userRepo->countForumMessages($user->getId())
            'projects' => 5,  // $userRepo->countProjects($user->getId())
            'snippets' => 8,  // $userRepo->countSnippets($user->getId())
            'likes' => 23     // $userRepo->countLikes($user->getId())
        ];

        // Passer les données à la vue
        $this->render('user/dashboard', [
            'user' => $user,
            'stats' => $stats
        ]);
    }

    public function uploadPhoto(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /?controller=auth&action=login');
            exit;
        }

        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception("Erreur lors de l'upload de la photo");
        }

        $uploadDir = APP_ROOT . '/public/photos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('user_' . $_SESSION['user_id'] . '_') . '.' . $extension;
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
            throw new \Exception("Impossible d'enregistrer la photo");
        }

        $userRepo = new UserRepository();
        $userRepo->updatePhoto($_SESSION['user_id'], $fileName);

        $photoUrl = '/photos/' . $fileName;

        // 🔹 Si c’est AJAX → JSON
        if (
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'photo' => $photoUrl
            ]);
            exit;
        }

        // 🔹 Sinon → redirection classique
        header('Location: /?controller=user&action=dashboard');
        exit;
    }
}
