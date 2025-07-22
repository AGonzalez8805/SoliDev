<?php

namespace App\Repository;

use App\Models\Blog;
use App\Db\Mysql;

class BlogRepository
{
    public function findOneById(int $id)
    {
        //Appel bdd
        $mysql = Mysql::getInstance();

        $pdo = $mysql->getPDO();

        $query = $pdo->prepare('SELECT * FROM blog WHERE id = :id');
        $query->bindValue(':id', $id, $pdo::PARAM_INT);
        $query->execute();
        $blog = $query->fetch(); //On en récupère qu'un

        // $blog = ['id' => 1, 'title' => 'titre test', 'description' => 'description test'];
        
        $blogModels = new Blog();
        $blogModels->setId($blog['id']);
        $blogModels->setTitle($blog['title']);
        $blogModels->setDescription($blog['description']);

        return $blogModels;
    }
}