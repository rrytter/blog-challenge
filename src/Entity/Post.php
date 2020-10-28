<?php

namespace App\Entity;

use App\Component\Database\DatabaseManager;
use DateTime;

class Post
{
    private DatabaseManager $databaseManager;
    private int $id;
    private string $title;
    private string $content;
    private string $status;
    private DateTime $created;
    private DateTime $updated;

    public function __construct(DatabaseManager $databaseManager, array $data = [])
    {
        $this->databaseManager = $databaseManager;
        $this->id = isset($data['id']) ? (int) $data['id'] : 0;
        $this->title = isset($data['title']) ? (string) $data['title'] : '';
        $this->content = isset($data['content']) ? (string) $data['content'] : '';
        $this->status = isset($data['status']) ? (string) $data['status'] : '';
        $this->created = isset($data['created']) ? new DateTime($data['created']) : new DateTime();
        $this->updated = isset($data['updated']) ? new DateTime($data['updated']) : new DateTime();
    }

    public static function getTable(): string
    {
        return 'posts';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setTitle(string $title): Post
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setContent(string $content): Post
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getExcerpt(): string
    {
        return strlen($this->content) > 200
            ? substr($this->content, 0, 200) . '...'
            : $this->content
        ;
    }

    public function setStatus(string $status): Post
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setCreated(DateTime $created): Post
    {
        $this->created = $created;

        return $this;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function setUpdated(DateTime $updated): Post
    {
        $this->updated = $updated;

        return $this;
    }

    public function getUpdated(): DateTime
    {
        return $this->updated;
    }

    public function persist(): bool
    {
        $query = $this->id
            ? "UPDATE `{$this->getTable()}` SET `title` = :title, `content` = :content, `status` = :status, `created` = :created, `updated` = :updated WHERE `id` = :id;"
            : "INSERT INTO `{$this->getTable()}` (`title`, `content`, `status`, `created`, `updated`) VALUES (:title, :content, :status, :created, :updated);"
        ;

        $params = [
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'created' => $this->created->format('Y-m-d'),
            'updated' => $this->updated->format('Y-m-d')
        ];

        if ($this->id) {
            $params['id'] = $this->id;
        }

        return $this->databaseManager->getConnection()
            ->prepare($query)
            ->execute($params)
        ;
    }

    public function delete(): bool
    {
        if (!$this->id) {
            return false;
        }

        return $this->databaseManager->getConnection()
            ->prepare("DELETE FROM `{$this->getTable()}` WHERE `id` = :id;")
            ->execute(['id' => $this->id])
        ;
    }

}