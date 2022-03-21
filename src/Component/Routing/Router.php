<?php

namespace App\Component\Routing;

class Router
{
    /**
     * @var Route[]
     */
    private array $routes;

    /**
     * @param Route[] $routes
     */
    public function __construct(array $routes = [])
    {
        $routes = array_filter($routes, function($route) {
            return $route instanceof Route;
        });
        $this->routes = array_combine(
            array_map(function($route) {
                return $route->getName();
            }, $routes),
           $routes 
        );
    }

    /**
     * @return Route[] 
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getRoute(string $name): ?Route
    {
        return $this->routes[$name];
    }

    public function addRoute(Route $route): Router
    {
        $this->routes[$route->getName()] = $route;

        return $this;
    }

    /**
     * @param Route[] $routes 
     */
    public function addRoutes(array $routes): Router
    {
        foreach ($routes as $route) {
            $this->addRoute($route);
        }

        return $this;
    }

    public function generate(string $name, array $parameters = []): string
    {
        if (!$route = $this->getRoute($name)) {
            return new \RuntimeException("Route $name not found");
        }
        
        return $route->generate($parameters);
    }

    public function resolve(string $path): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->match($path)) {
                return $route;
            }
        }

        return null;
    }
}