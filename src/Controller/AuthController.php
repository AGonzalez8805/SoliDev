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
                    case 'handleRegister';
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
        $this->render('auth/registration');
    }


    public function handleLogin()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userRepo = new UserRepository();
        $user = $userRepo->findByEmail($email);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            //Connexion réussie
            session_start();
            if ($user['rôle'] === 'admin') {
                $_SESSION['admin_id'] = $user['id_user'];
            } else {
                $_SESSION['user_id'] = $user['id_user'];
            }
            header('Location: index.php');
        } else {
            //Connexion echoué
            $this->render('auth/login', ['error' => 'Identification incorrects']);
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
        ]);

        if ($result) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de l'inscription."]);
        }
    }
}
