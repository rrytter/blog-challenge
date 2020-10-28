<?php

namespace App\Component\Router;

class Router
{
    private array $routes;

    /**
     * @param Route[] $routes
     */
    public function __construct(array $routes = [])
    {
        $this->routes = array_combine(
            array_map(function($route) {
                return $route->getName();
            }, $routes),
           $routes 
        );
    }

    public function resolve(string $path): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->resolve($path)) {
                return $route;
            }
        }

        return null;
    }
}