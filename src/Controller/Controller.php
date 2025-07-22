<?php

namespace App\Controller;

class Controller{
    public function route(): void //Ne retourne rien
    {
        if (isset($_GET['controller'])){
            switch ($_GET['controller']) {
                case 'page':
                    //charger controleur page
                    $pageController = new PageController();
                    $pageController->route();
                    break;
                
                default:
                    # code...
                    break;
            }
        }else {
            //Charger la page d'accueil
        }
    }

    protected function render(string $path, array $params = []):void
    {
        $filePath = _ROOTPATH_. '/views/'.$path.'.php';

        if(!file_exists($filePath)){
            // Générer erreur
        }else {
            require_once $filePath;
        }
    }
}