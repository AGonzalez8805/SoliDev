<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Models\User;
use App\Service\UserService;
use App\Config\Mailer;

class UserController extends Controller
{
    public function route(string $action = 'dashboard'): void
    {
        $this->handleRoute(function () use ($action) {
            switch ($action) {
                case 'dashboard':
                    $this->dashboard();
                    break;
                case 'updateProfile':
                    $this->updateProfile();
                    break;
                case 'uploadPhoto':
                    $this->uploadPhoto();
                case 'register':
                    $this->register();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                case 'update':
                    $this->update();
                    break;

                default:
                    throw new \Exception("Action utilisateur inconnue");
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
            ->setPhoto($userData['photo'] ?? null)
            ->setGithubUrl($userData['github_url'] ?? null)
            ->setLinkedinUrl($userData['linkedin_url'] ?? null)
            ->setWebsiteUrl($userData['website_url'] ?? null)
            ->setBio($userData['bio'] ?? null)
            ->setSkills($userData['skills'] ?? null);

        $activities = $userRepo->findRecentByUser($_SESSION['user_id']);

        $stats = $userRepo->getStats($_SESSION['user_id']);

        $this->render('user/dashboard', [
            'user' => $user,
            'activities' => $activities,
            'stats' => $stats
        ]);
    }

    public function updateProfile(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        // Assure-toi que la session est démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
            exit;
        }

        // Récupère les données POST ou JSON
        $input = $_POST;

        // Validation email
        if (isset($input['email']) && !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Adresse email invalide']);
            exit;
        }

        $userRepo = new UserRepository();
        $success = $userRepo->updateProfile($userId, $input);

        if ($success) {
            echo json_encode([
                'success' => true,
                'newFirstName' => $input['firstName'] ?? '',
                'newName' => $input['name'] ?? ''
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
        }

        exit;
    }

    public function uploadPhoto(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
            exit;
        }

        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Aucun fichier reçu']);
            exit;
        }

        $file = $_FILES['photo'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = "user_{$userId}_" . time() . "." . $ext;
        $target = __DIR__ . "/../../public/photos/" . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            echo json_encode(['success' => false, 'message' => 'Impossible de sauvegarder le fichier']);
            exit;
        }

        $userRepo = new UserRepository();
        $userRepo->updatePhoto($userId, $fileName);

        echo json_encode(['success' => true, 'photo' => "/photos/$fileName"]);
        exit;
    }


    public function register(): void
    {
        $name = $_POST['name'] ?? '';
        $firstName = $_POST['firstName'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $validatePassword = $_POST['validatePassword'] ?? '';

        if ($password !== $validatePassword) {
            $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
            header('Location: /?controller=user&action=registrationForm');
            exit;
        }

        $config = [
            'host' => $_ENV['MAIL_HOST'],
            'username' => $_ENV['MAIL_USERNAME'],
            'password' => $_ENV['MAIL_PASSWORD'],
            'port' => $_ENV['MAIL_PORT'],
            'encryption' => $_ENV['MAIL_SMTP_SECURE'],
            'from_email' => $_ENV['MAIL_FROM'],
            'from_name' => $_ENV['MAIL_FROM_NAME'],
        ];

        $mailer = new Mailer(true);
        $userService = new UserService(new UserRepository(), $mailer);

        $result = $userService->registerUser($name, $firstName, $email, $password);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
            header('Location: /?controller=auth&action=login');
        } else {
            $_SESSION['error'] = $result['message'];
            header('Location: /?controller=user&action=registrationForm');
        }
        exit;
    }

    //Suppression et mise à jour d'un utilisateur via AJAX (AdminDashboard)
    public function delete()
    {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $id) {
            $userRepository = new UserRepository();
            $success = $userRepository->delete($id);

            echo json_encode(['success' => $success]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    }

    public function update()
    {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $data = json_decode(file_get_contents('php://input'), true);
            $name = $data['name'] ?? null;

            $userRepository = new UserRepository();
            $success = $userRepository->update($id, ['name' => $name]);

            echo json_encode(['success' => $success]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    }
}
