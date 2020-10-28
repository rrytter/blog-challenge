<?php

namespace App\Component\Kernel;

use App\Component\Database\DatabaseManager;
use App\Component\Router\Router;
use App\Component\Kernel\Exception\NotFoundException;
use App\DataFixtures\PostFixtures;

class Kernel
{
    private string $projectDir;
    private Router $router;
    private DatabaseManager $databaseManager;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
        $this->router = new Router(
            require("{$this->getConfigDir()}/routes.php")
        );
        $this->databaseManager = (new DatabaseManager(
            require("{$this->getConfigDir()}/database.php")
        ))->connect();

        if ($this->databaseManager->isEmpty()) {
            (new PostFixtures($this->databaseManager))->load();
        }
    }

    public function getProjectDir(): string
    {
        return $this->projectDir;
    }

    public function getConfigDir(): string
    {
        return "$this->projectDir/config";
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function getDatabaseManager(): DatabaseManager
    {
        return $this->databaseManager;
    }

    public function run()
    {
        $path = '/' . trim($_SERVER['REQUEST_URI'] ?? '/', '/');
        
        if ($route = $this->router->resolve($path)) {

            $controller = (new \ReflectionClass($route->getController()))
                ->newInstance($this)
            ;
            
            return call_user_func_array(
                [$controller, $route->getMethod()],
                array_values($route->getParameters())
            );
            
        }

        throw new NotFoundException("Requested resource $path not found");
    }
}