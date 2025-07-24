<?php

namespace App\Controller;

use App\Repository\UserRepository;

class AuthController extends Controller
{
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
                        break;
                }
            } else {
                throw new \Exception("Aucune action détecté");
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
        }
    }

    public function login()
    {
        $this->render('auth/login');
    }
    public function registration()
    {
        $userRepo = new UserRepository();
        if ($userRepo->findByRole('admin')) {
            // Un admin existe déjà, redirection vers login
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        $this->render('auth/registration');
    }


    public function handleLogin()
    {
        // Dans App\Controller\AuthController.php

        // Lire les données JSON envoyées par fetch
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo json_encode(["success" => false, "message" => "Données invalides."]);
            return;
        }

        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $remember = $input['remember'] ?? false; // Capturer 'rememberMe' si vous prévoyez de l'utiliser

        // Validation de base
        if (empty($email) || empty($password)) {
            echo json_encode(["success" => false, "message" => "L'email et le mot de passe sont requis."]);
            return;
        }

        $userRepo = new UserRepository();
        $user = $userRepo->findByEmail($email);

        // Vérifier l'identité et le rôle
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            session_start();
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['rôle']; // Stocker le rôle de l'utilisateur

            // Si 'se souvenir de moi' est coché, vous pourriez définir un cookie ici
            if ($remember) {
                //     Définir un cookie de longue durée pour se souvenir de l'utilisateur
            }

            echo json_encode(["success" => true]); // Envoyer une réponse JSON de succès
            return;
        } else {
            echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect."]); // Envoyer une réponse JSON d'échec
            return;
        }
    }


    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: index.php');
        exit;
    }


    public function handleRegister()
    {
        //Lire les données JSON envoyées par fetch
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

        // Déterminer le rôle du nouvel utilisateur
        $roleToAssign = 'utilisateur'; // Rôle par défaut
        if (!$userRepo->findByRole('admin')) {
            // Si aucun administrateur n'existe, cet utilisateur sera l'administrateur
            $roleToAssign = 'admin';
        }

        // Vérifier si l'email existe déjà
        if ($userRepo->findByEmail($email)) {
            echo json_encode(["success" => false, "message" => "Cet email est déjà utilisé."]);
            return;
        }

        // Hacher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Créer l'utilisateur
        $result = $userRepo->create([
            'nom' => $name,
            'prenom' => $firstName,
            'email' => $email,
            'mot_de_passe' => $hashedPassword,
            'rôle' => $roleToAssign // Utiliser le rôle déterminé
        ]);

        if ($result) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de l'inscription."]);
        }
    }
}
