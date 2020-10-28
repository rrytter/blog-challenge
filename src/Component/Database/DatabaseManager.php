<?php

namespace App\Component\Database;

class DatabaseManager
{
    private array $parameters;
    private ?\PDO $connection;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
        $this->connection = null;
    }

    public function connect(): DatabaseManager
    {
        if (!$this->isConnected()) {
            $this->connection = new \PDO(...$this->parameters);
        }

        return $this;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function isConnected(): bool
    {
        return $this->connection instanceof \PDO;
    }

    public function getConnection(): ?\PDO
    {
        return $this->connection;
    }

    public function isEmpty(): bool
    {
        if (preg_match('/^sqlite:(.+)$/', $this->parameters[0], $matches)) {
            return !file_exists($matches[1]) || !filesize($matches[1]);
        }

        return false;
    }
}