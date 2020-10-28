<?php

namespace App\Component\Router;

class Route
{
    private string $name;
    private string $pattern;
    private string $controller;
    private string $method;
    private array $parameters;

    public function __construct(string $name, string $pattern, array $handler)
    {
        $this->name = $name;
        $this->pattern = $pattern;
        [$this->controller, $this->method] = $handler;
        $this->parameters = [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function resolve(string $path): bool
    {
        if (preg_match($this->pattern, $path, $matches)) {

            $this->parameters = array_values(array_slice($matches, 1));

            return true;
        }

        return false;
    }
}