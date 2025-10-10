<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Config\Mailer;

class AuthController extends Controller
{
    /**
     * Méthode routeur pour rediriger vers la bonne action en fonction de la query string.
     * Gère les erreurs en AJAX ou en HTTP standard selon le contexte de la requête.
     */
    public function route(string $action = 'login'): void
    {
        $this->handleRoute(function () use ($action) {
            switch ($action) {
                case 'login':
                    $this->login();
                    break;
                case 'handleLogin':
                    $this->handleLogin();
                    break;
                case 'logout':
                    $this->logout();
                    break;
                case 'registration':
                    $this->registration();
                    break;
                case 'handleRegister':
                    $this->handleRegister();
                    break;
                case 'confirmEmail':
                    $this->confirmEmail();
                    break;
                default:
                    throw new \Exception("Cette action n'existe pas : " . $action);
            }
        });
    }

    /**
     * Affiche la page de connexion
     */
    public function login()
    {
        $this->render('auth/login');
    }

    /**
     * Affiche la page d'inscription
     */
    public function registration()
    {
        $this->render('auth/registration');
    }

    /**
     * Gère la soumission du formulaire de connexion via AJAX (JSON)
     */
    public function handleLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Nettoyer tout buffer précédent
        while (ob_get_level()) ob_end_clean();

        header('Content-Type: application/json');
        http_response_code(200);

        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input || empty($input['email']) || empty($input['password'])) {
            echo json_encode([
                "success" => false,
                "message" => "Email et mot de passe requis."
            ]);
            return;
        }

        $email = trim($input['email']);
        $password = $input['password'];

        $userRepo = new UserRepository();
        $user = $userRepo->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['users_id'] ?? $user['id'] ?? null;
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $redirect = $_SESSION['redirect_after_login'] ?? null;
            unset($_SESSION['redirect_after_login']);

            if (!$redirect) {
                // Si pas de redirection spécifique, dashboard selon le rôle
                $redirect = $user['role'] === 'admin'
                    ? '/?controller=admin&action=dashboard'
                    : '/?controller=user&action=dashboard';
            }

            echo json_encode(["success" => true, "redirect" => $redirect]);
        } else {
            echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect."]);
        }
    }

    /**
     * Déconnexion de l'utilisateur, suppression de la session et redirection vers la page d'accueil.
     */
    public function logout()
    {
        session_unset();    // Supprime toutes les variables de session
        session_destroy();  // Détruit la session
        header('Location: /?controller=page&action=home');
        exit;
    }

    /**
     * Gère l'inscription d'un nouvel utilisateur via AJAX (JSON)
     */
    public function handleRegister()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        while (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json');
        http_response_code(200);

        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input || empty($input['name']) || empty($input['firstName']) || empty($input['email']) || empty($input['password'])) {
            echo json_encode(["success" => false, "message" => "Tous les champs sont requis."]);
            return;
        }

        $name = trim($input['name']);
        $firstName = trim($input['firstName']);
        $email = trim($input['email']);
        $password = $input['password'];
        $validatePassword = $input['validatePassword'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["success" => false, "message" => "Email invalide."]);
            return;
        }

        if ($password !== $validatePassword) {
            echo json_encode(["success" => false, "message" => "Les mots de passe ne correspondent pas."]);
            return;
        }

        $userRepo = new UserRepository();
        if ($userRepo->findByEmail($email)) {
            echo json_encode(["success" => false, "message" => "Email déjà utilisé."]);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $token = bin2hex(random_bytes(16)); // Génère un token unique

        $result = $userRepo->create([
            'name' => $name,
            'firstName' => $firstName,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'utilisateur',
            'email_verification_token' => $token,
            'is_email_verified' => 0
        ]);

        if ($result) {
            // Envoyer le mail de confirmation
            $mailer = new Mailer(true);
            $appUrl = $_ENV['APP_URL'] ?? getenv('APP_URL') ?? 'https://localhost:90';
            $verificationLink = "$appUrl/?controller=auth&action=confirmEmail&token=$token";
            $subject = "Confirme ton compte SoliDev";
            $body = "<p>Bonjour $firstName,</p>
                    <p>Merci pour ton inscription. Clique sur le lien ci-dessous pour activer ton compte :</p>
                    <p><a href='$verificationLink'>$verificationLink</a></p>";

            $mailer->sendMail($email, $subject, $body);

            echo json_encode(["success" => true, "message" => "Inscription réussie. Vérifie ton email pour confirmer ton compte."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de l'inscription."]);
        }
    }

    public function confirmEmail()
    {
        $token = $_GET['token'] ?? null;
        if (!$token) {
            $this->render('auth/confirmation', ['message' => 'Lien invalide.']);
            return;
        }

        $userRepo = new UserRepository();
        $user = $userRepo->findByToken($token);

        if (!$user) {
            $this->render('auth/confirmation', ['message' => 'Lien de confirmation invalide ou expiré.']);
            return;
        }

        $userRepo->verifyUser($user['users_id']);
        $this->render('auth/confirmation', ['message' => 'Ton compte est maintenant activé ! Tu peux te connecter.']);
    }
}
