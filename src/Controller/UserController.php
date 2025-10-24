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
                    break;
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

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
            exit;
        }

        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] === UPLOAD_ERR_NO_FILE) {
            echo json_encode(['success' => false, 'message' => 'Aucun fichier reçu']);
            exit;
        }

        $file = $_FILES['photo'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors = [
                UPLOAD_ERR_INI_SIZE => 'Le fichier est trop grand (php.ini).',
                UPLOAD_ERR_FORM_SIZE => 'Le fichier dépasse la taille maximale du formulaire.',
                UPLOAD_ERR_PARTIAL => 'Le fichier n\'a été que partiellement téléchargé.',
                UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant.',
                UPLOAD_ERR_CANT_WRITE => 'Impossible d\'écrire le fichier sur le disque.',
                UPLOAD_ERR_EXTENSION => 'Une extension PHP a stoppé le téléchargement.'
            ];
            $msg = $errors[$file['error']] ?? 'Erreur inconnue lors du téléchargement.';
            echo json_encode(['success' => false, 'message' => $msg]);
            exit;
        }

        // Vérification type MIME
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedMimes)) {
            echo json_encode(['success' => false, 'message' => 'Format non autorisé. Utilisez JPG, PNG, GIF ou WEBP']);
            exit;
        }

        // Limite taille 20 Mo (selon votre uploads.ini)
        if ($file['size'] > 20 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'Image trop lourde (max 20 Mo)']);
            exit;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileName = "user_{$userId}_" . time() . "." . $ext;

        // Chemin absolu depuis DocumentRoot
        $targetDir = '/var/www/html/public/photos/';

        // Vérifications de sécurité
        if (!is_dir($targetDir)) {
            if (!mkdir($targetDir, 0775, true)) {
                error_log("Impossible de créer le dossier: " . $targetDir);
                echo json_encode(['success' => false, 'message' => 'Erreur de configuration du serveur']);
                exit;
            }
        }

        if (!is_writable($targetDir)) {
            error_log("Dossier non accessible en écriture: " . $targetDir);
            echo json_encode(['success' => false, 'message' => 'Erreur de permissions sur le serveur']);
            exit;
        }

        $target = $targetDir . $fileName;

        // Redimensionnement
        if (!$this->resizeImage($file['tmp_name'], $target, 1024, 1024)) {
            error_log("Échec du redimensionnement de l'image");
            echo json_encode(['success' => false, 'message' => 'Erreur lors du traitement de l\'image']);
            exit;
        }

        // Sécuriser les permissions du fichier
        chmod($target, 0644);

        // Mise à jour en base
        $userRepo = new UserRepository();
        if (!$userRepo->updatePhoto($userId, $fileName)) {
            if (file_exists($target)) {
                unlink($target);
            }
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la sauvegarde en base de données']);
            exit;
        }

        echo json_encode(['success' => true, 'photo' => "/photos/$fileName"]);
        exit;
    }

    private function resizeImage(string $sourcePath, string $targetPath, int $maxWidth, int $maxHeight): bool
    {
        // Récupère les informations de l'image
        $imageInfo = getimagesize($sourcePath);
        if ($imageInfo === false) {
            error_log("Impossible de lire les informations de l'image: " . $sourcePath);
            return false;
        }

        [$width, $height, $type] = $imageInfo;

        // Calcul du ratio pour conserver les proportions
        $ratio = min($maxWidth / $width, $maxHeight / $height, 1);
        $newWidth = (int)($width * $ratio);
        $newHeight = (int)($height * $ratio);

        // Création de l'image source selon le type
        switch ($type) {
            case IMAGETYPE_JPEG:
                $src = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $src = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $src = imagecreatefromgif($sourcePath);
                break;
            case IMAGETYPE_WEBP:
                $src = imagecreatefromwebp($sourcePath);
                break;
            default:
                error_log("Type d'image non supporté: " . $type);
                return false;
        }

        if (!$src) {
            error_log("Impossible de créer l'image source depuis: " . $sourcePath);
            return false;
        }

        // Création de l'image de destination
        $dst = imagecreatetruecolor($newWidth, $newHeight);

        if (!$dst) {
            imagedestroy($src);
            error_log("Impossible de créer l'image de destination");
            return false;
        }

        // Préserve la transparence pour PNG et GIF
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF || $type == IMAGETYPE_WEBP) {
            imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }

        // Redimensionnement
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Sauvegarde selon le type
        $success = false;
        switch ($type) {
            case IMAGETYPE_JPEG:
                $success = imagejpeg($dst, $targetPath, 90);
                break;
            case IMAGETYPE_PNG:
                $success = imagepng($dst, $targetPath, 9);
                break;
            case IMAGETYPE_GIF:
                $success = imagegif($dst, $targetPath);
                break;
            case IMAGETYPE_WEBP:
                $success = imagewebp($dst, $targetPath, 90);
                break;
        }

        // Libération de la mémoire
        imagedestroy($src);
        imagedestroy($dst);

        if (!$success) {
            error_log("Échec de la sauvegarde de l'image redimensionnée: " . $targetPath);
        }

        return $success;
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
