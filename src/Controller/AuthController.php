<?php

namespace App\Controller;

use App\Repository\UserRepository;

class AuthController extends Controller
{
    public function route(): void 
    {
            try {
                if (isset($_GET['action'])){
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
                    default:
                        throw new \Exception("Cette action n'existe pas : ".$_GET['action']);
                        break;
                }
            }else {
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
        $email = $_POST['email']??'';
        $password = $_POST['password']??'';

        $userRepo = new UserRepository();
        $user = $userRepo->findByEmail($email);

        if($user && password_verify($password, $user['mot_de_passe'])){
            //Connexion réussie
            session_start();
            if($user['rôle'] === 'admin'){
                $_SESSION['admin_id'] = $user['id_user'];
            }else {
                $_SESSION['user_id'] = $user['id_user'];
            }
            header('Location: index.php');
        }else {
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
}