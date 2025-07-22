<?php

namespace App\Controller;

class Controller{
    public function route(): void //Ne retourne rien
    {
        if (isset($_GET['controller'])){
            switch ($_GET['controller']) {
                case 'page':
                    //charger controleur page
                    var_dump('On charge PageController');
                    break;
                case 'projet':
                    //charger controleur projet
                    var_dump('On charge ProjetController');
                    break;
                case 'forum':
                    //charger controleur projet
                    var_dump('On charge ForumController');
                    break;
                case 'Auth':
                    //charger controleur projet
                    var_dump('On charge AuthController');
                    break;
                case 'blog':
                    //charger controleur projet
                    var_dump('On charge BlogController');
                    break;
                case 'snippet':
                    //charger controleur projet
                    var_dump('On charge SnippetController');
                    break;
                case 'user':
                    //charger controleur projet
                    var_dump('On charge UserController');
                    break;
                
                default:
                    # code...
                    break;
            }
        }else {
            //Charger la page d'accueil
        }
    }
}