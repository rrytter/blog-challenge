<?php

namespace App\Component\Kernel;

use App\Component\Database\DatabaseManager;
use App\Component\Routing\Router;
use App\Component\Kernel\Exception\NotFoundException;
use App\Component\Routing\Attribute\Route as RouteAttribute;
use App\Component\Routing\Route;
use App\DataFixtures\PostFixtures;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use ReflectionClass;
use RegexIterator;

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
        return "{$this->getProjectDir()}/config";
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

        $directory = $this->getProjectDir() . '/src/Controller';
        $controllers = new RegexIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory)
            ),
            "/^.+([A-Z](?:[A-Za-z0-9]+)?Controller)\.php$/",
            RecursiveRegexIterator::GET_MATCH
        );
        foreach ($controllers as list(, $controller)) {
            $reflection = new ReflectionClass("\\App\\Controller\\$controller");
            foreach ($reflection->getMethods() as $method) {
                foreach ($method->getAttributes(RouteAttribute::class) as $attribute) {
                    $routeAttribute = $attribute->newInstance();
                    assert($routeAttribute instanceof RouteAttribute);
                    $this->getRouter()->addRoute(
                        new Route(
                            $routeAttribute->getName() ?? "{$reflection->getName()}::{$method->getName()}",
                            $routeAttribute->getPath(),
                            $reflection->getName(),
                            $method->getName(),
                            $routeAttribute->getDefaults(),
                            $routeAttribute->getRequirements()
                        )
                    );
                }
            }
        }

        if ($route = $this->getRouter()->resolve($path)) {

            $controller = (new \ReflectionClass($route->getController()))
                ->newInstance($this)
            ;

            if (
                !method_exists($controller, $route->getMethod())
                || !is_callable([$controller, $route->getMethod()])
            ) {
                throw new \RuntimeException("Method {$route->getMethod()} is not callable in " . $controller::class . " (Route {$route->getName()})");
            }
            
            return call_user_func_array(
                [$controller, $route->getMethod()],
                array_values($route->getParameters())
            );
            
        }

        throw new NotFoundException("Requested resource $path not found");
    }
}