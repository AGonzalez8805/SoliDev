<?php

namespace App\Controller;

use App\Repository\UserRepository;

class AuthController extends Controller
{
    /**
     * Méthode routeur pour rediriger vers la bonne action en fonction de la query string.
     * Gère les erreurs en AJAX ou en HTTP standard selon le contexte de la requête.
     */
    public function route(): void
    {
        $this->handleRoute(function () {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
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
                    default:
                        throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
                }
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

            $url = $user['role'] === 'admin'
                ? '/?controller=admin&action=dashboard'
                : '/?controller=user&action=dashboard';

            echo json_encode(["success" => true, "redirect" => $url]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Email ou mot de passe incorrect."
            ]);
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
            echo json_encode([
                "success" => false,
                "message" => "Tous les champs sont requis."
            ]);
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

        $result = $userRepo->create([
            'name' => $name,
            'firstName' => $firstName,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'utilisateur'
        ]);

        if ($result) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de l'inscription."]);
        }
    }
}
