<?php

namespace App\Controller;

class ProjectController extends Controller
{
    public function route(string $action = 'project'): void
    {
        $this->handleRoute(function () use ($action) {
            switch ($action) {
                case 'project':
                    $this->project();
                    break;
                case 'create':
                    $this->create();
                    break;
                default:
                    throw new \Exception("Cette action n'existe pas : " . $action);
                    break;
            }
        });
    }

    protected function project(): void
    {
        $this->render('project/project');
    }

    protected function create(): void
    {
        $this->render('project/create');
    }
}
