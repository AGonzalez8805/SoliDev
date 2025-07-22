<?php

namespace App\Repository;

use App\Models\Blog;

class BlogRepository
{
    public function findOneById(int $id)
    {
        //Appel bdd
        $blog = ['id' => 1, 'title' => 'titre test', 'description' => 'description test'];
        
        $blogModels = new Blog();
        $blogModels->setId($blog['id']);
        $blogModels->setTitle($blog['title']);
        $blogModels->setDescription($blog['description']);

        return $blogModels;
    }
}