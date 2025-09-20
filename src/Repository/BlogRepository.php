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

    public function findAll(): array
    {
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $query = $pdo->query('SELECT * FROM blog ORDER BY created_at DESC');
        $rows = $query->fetchAll();

        $blogs = [];
        foreach ($rows as $row) {
            $blog = new Blog();
            $blog->setId($row['id'])
                ->setTitle($row['title'])
                ->setDescription($row['description']);
            $blogs[] = $blog;
        }

        return $blogs;
    }

    public function insert(string $title, string $description): void
    {
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $query = $pdo->prepare('INSERT INTO blog (title, description) VALUES (:title, :description)');
        $query->bindValue(':title', $title, \PDO::PARAM_STR);
        $query->bindValue(':description', $description, \PDO::PARAM_STR);
        $query->execute();
    }
}
