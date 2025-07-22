<?php

namespace App\Controller;

class PageController extends Controller
{
     public function route(): void //Ne retourne rien
    {
        if (isset($_GET['action'])){
            switch ($_GET['action']) {
                case 'home':
                    // appeler la méthode home()
                    $this->home();
                    break;
                case 'about':
                    // appeler la méthode about();
                    $this->about();
                
                default:
                    //Erreur
                    break;
            }
        }else {
            //Charger la page d'accueil
        }
    }

    protected function about()
    {
        // On pourrait récupérer les données
        // en faisant appel au modèle

        $this->render('page/about');
    }

        protected function home()
    {
        // On pourrait récupérer les données
        // en faisant appel au modèle

        $this->render('page/home');
    }
}