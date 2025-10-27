<?php

namespace App\Controller;

class SnippetsController extends Controller
{
    public function route(string $action = 'snippets'): void
    {
        $this->handleRoute(function () use ($action) {
            switch ($action) {
                case 'snippets':
                    $this->snippets();
                    break;
                case 'createSnippet':
                    $this->createSnippet();
                    break;

                default:
                    throw new \Exception("Cette action n'existe pas: " . $action);
            }
        });
    }

    public function snippets(): void
    {
        $this->render('snippets/snippets');
    }

    public function createSnippet(): void
    {
        $this->render('snippets/createSnippet');
    }
}
