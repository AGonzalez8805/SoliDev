<?php

namespace App\Controller;

class Controller{
    public function route(): void //Ne retourne rien
    {
        try {
            if (isset($_GET['controller'])){
                switch ($_GET['controller']) {
                    case 'page':
                        //charger controleur page
                        $pageController = new PageController();
                        $pageController->route();
                        break;
                    
                    default:
                        throw new \Exception("Le controleur n'existe pas");
                        break;
                }
            }else {
                //Charger la page d'accueil
            }
        } catch (\Exception $e) {
            $this->render('errors/default' , [
                'error' => $e->getMessage()
            ]);
            
        }

    }

    protected function render(string $path, array $params = []):void
    {
        $filePath = _ROOTPATH_. '/views/'.$path.'.php';
        try {
                if(!file_exists($filePath)){
                throw new \Exception("Fichier non trouvé :".$filePath);
            }else {
                extract($params);
                require_once $filePath;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}