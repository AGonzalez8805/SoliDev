<?php

namespace App\Controller;

class ForumController extends Controller
{
    public function route(): void
    {
        $this->handleRoute(function () {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'forum':
                        $this->forum();
                        break;

                    default:
                        throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
                }
            }
        });
    }

    public function forum()
    {
        $this->render('forum/forum');
    }
}
