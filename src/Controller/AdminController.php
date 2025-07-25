<?php

namespace App\Controller;

class AdminController extends Controller
{
    public function route(): void
    {
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'dashboard':
                    $this->dashboard();
                    break;
                default:
                    throw new \Exception("Action admin inconnue");
            }
        }
    }

    public function dashboard(): void
    {

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?controller=auth&action=login');
            exit;
        }

        $this->render('admin/dashboard');
    }
}
