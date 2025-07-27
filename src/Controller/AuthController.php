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
        try {
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
            } else {
                throw new \Exception("Aucune action détectée");
            }
        } catch (\Exception $e) {
            // Gestion d'erreurs spécifique pour les requêtes AJAX
            if (
                isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            ) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            } else {
                // Affichage d'une vue d'erreur standard
                $this->render('errors/default', [
                    'errors' => $e->getMessage()
                ]);
            }
        }
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
        ob_start(); // Capture le buffer de sortie pour éviter les interférences avec les headers
        header('Content-Type: application/json');
        http_response_code(200);

        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo json_encode(["success" => false, "message" => "Données invalides."]);
            return;
        }

        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $remember = $input['remember'] ?? false;

        // Vérification que les champs obligatoires sont remplis
        if (empty($email) || empty($password)) {
            echo json_encode(["success" => false, "message" => "L'email et le mot de passe sont requis."]);
            return;
        }

        $userRepo = new UserRepository();
        $user = $userRepo->findByEmail($email);

        // Vérification des identifiants
        if ($user && password_verify($password, $user['password'])) {
            // Authentification réussie, on stocke les infos en session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Implémentation future possible : cookie de persistance avec $remember
            if ($remember) {
                // Exemple : setcookie("rememberMe", ...);
            }

            ob_end_clean(); // Nettoie le buffer de sortie

            // Redirection conditionnelle selon le rôle
            $url = ($_SESSION['role'] === 'admin')
                ? '/?controller=admin&action=dashboard'
                : '/?controller=user&action=dashboard';

            echo json_encode(["success" => true, "redirect" => $url]);
            return;
        } else {
            // Identifiants incorrects
            echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect."]);
            return;
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
        header('Content-Type: application/json');
        http_response_code(200);

        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo json_encode(["success" => false, "message" => "Données invalides"]);
            return;
        }

        // Sécurité : nettoyage + validation
        $name = trim($input['name'] ?? '');
        $firstName = trim($input['firstName'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $validatePassword = $input['validatePassword'] ?? '';

        // Vérification de champs obligatoires
        if (empty($name) || empty($firstName) || empty($email) || empty($password)) {
            echo json_encode(["success" => false, "message" => "Tous les champs sont requis."]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["success" => false, "message" => "Adresse email invalide."]);
            return;
        }

        if ($password !== $validatePassword) {
            echo json_encode(["success" => false, "message" => "Le mot de passe ne correspond pas."]);
            return;
        }

        $userRepo = new UserRepository();

        // Vérifie si c’est le premier admin à inscrire
        $roleToAssign = 'utilisateur'; // Rôle par défaut
        if (!$userRepo->findByRole('admin')) {
            $roleToAssign = 'admin'; // Premier inscrit = admin
        }

        // Vérifie l’unicité de l’email
        if ($userRepo->findByEmail($email)) {
            echo json_encode(["success" => false, "message" => "Cet email est déjà utilisé."]);
            return;
        }

        // Hashage sécurisé du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Création du nouvel utilisateur
        $result = $userRepo->create([
            'name' => $name,
            'firstName' => $firstName,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $roleToAssign
        ]);

        if ($result) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de l'inscription."]);
        }
    }
}
