<?php

namespace App\Controller;

use App\Repository\BlogRepository;

class BlogController extends Controller
{
    public function route(): void 
    {
            try {
                if (isset($_GET['action'])){
                switch ($_GET['action']) {
                    case 'show':
                        $this->show();
                        break;
                    case 'list':
                        //Appeler méthode list()
                        break;
                    case 'edit':
                        //Appeler méthode edit()
                        break;
                    case 'store':
                        // Appeler méthode store()
                        break;
                    case 'create':
                        //Appeler méthode create()
                        break;
                    case 'update':
                        //Appeler méthode update()
                        break;
                    case 'delete':
                        //Appeler méthode delete()
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

    protected function show()
    {
        try {
            if (isset($_GET['id'])){
                $id = (int)$_GET['id'];
                //Charger le livre pa un appel au repository
                $blogRepository= new BlogRepository();
                $blog = $blogRepository->findOneById($id);

                $this->render('blog/show', [
                    'blog' => $blog
        ]);

            }else{
                throw new \Exception("L'id est manquant en paramètre");
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
        }
    }
}