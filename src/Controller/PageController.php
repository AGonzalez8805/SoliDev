<?php

namespace App\Controller;

class PageController extends Controller
{
    public function route(): void 
    {
            try {
                if (isset($_GET['action'])){
                switch ($_GET['action']) {
                    case 'about':
                        // appeler la méthode about();
                        $this->about();
                        break;
                    case 'home':
                        // appeler la méthode home()
                        $this->home();
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

    protected function about()
    {
        $this->render('page/about', [
            'test' => 'abc',
            'test2'=>'abc2'
        ]);
    }

    protected function home()
    {
        $this->render('page/home', [
            
        ]);
    }
}