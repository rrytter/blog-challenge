<?php

namespace App\Repository;

use App\Component\Database\DatabaseManager;
use App\Entity\Post;

class PostRepository
{
    private DatabaseManager $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * @return Post[]
     */
    public function findAll(): array
    {
        $rows = $this->databaseManager->getConnection()
            ->query(sprintf('SELECT * FROM `%s`;', Post::getTable()))
            ->fetchAll()
        ;

        if (!$rows) {
            return [];
        }

        return array_map(function($row) {
            return new Post($this->databaseManager, $row);
        }, $rows);
    }

    /**
     * @return Post[]
     */
    public function findAllPublished(): array
    {
        $rows = $this->databaseManager->getConnection()
            ->query(sprintf('SELECT * FROM `%s` WHERE `status` = "publish";', Post::getTable()))
            ->fetchAll()
        ;

        if (!$rows) {
            return [];
        }

        return array_map(function($row) {
            return new Post($this->databaseManager, $row);
        }, $rows);
    }
    
    public function findOneById(int $id): ?Post
    {
        $sth = $this->databaseManager->getConnection()
            ->prepare(sprintf('SELECT * FROM `%s` WHERE `id` = :id;', Post::getTable()))
        ;
        
        if (!$sth->execute(['id' => $id])) {
            return null;
        }

        return new Post($this->databaseManager, $sth->fetch());
    }

    public function findOneByIdAndPublished(int $id): ?Post
    {
        $sth = $this->databaseManager->getConnection()
            ->prepare(sprintf('SELECT * FROM `%s` WHERE `id` = :id AND `status` = "publish";', Post::getTable()))
        ;
        
        if (!$sth->execute(['id' => $id])) {
            return null;
        }

        return new Post($this->databaseManager, $sth->fetch());
    }
}