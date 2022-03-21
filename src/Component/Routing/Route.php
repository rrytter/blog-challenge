<?php

namespace App\Component\Routing;

class Route
{
    private string $name;
    private string $route;
    private string $controller;
    private string $method;
    private array $defaults;
    private array $requirements;
    private array $parameters;
    private ?string $compiled;

    public function __construct(string $name, string $route, string $controller, string $method, array $defaults = [], array $requirements = [])
    {
        $this->name = $name;
        $this->route = '/' . trim($route, '/');
        $this->controller = $controller;
        $this->method = $method;
        $this->defaults = $defaults;
        $this->requirements = $requirements;
        $this->parameters = [];
        $this->compiled = null;
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

    public function getDefaults(): array
    {
        return $this->defaults;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }

    public function getParameters(): array
    {
        return array_combine(
            array_column($this->parameters, 'name'),
            array_column($this->parameters, 'value')
        );
    }

    public function match(string $path): bool
    {
        if (preg_match("/^{$this->compile()}$/", $path, $matches)) {

            foreach (array_slice($matches, 1) as $index => $value) {
                $this->parameters[$index]['value'] = $value;
            }

            return true;
        }

        return false;
    }

    public function generate(array $parameters = []): string
    {
        $route = $this->route;
        $parameters += $this->defaults;

        foreach ($this->getSlugs() as $name) {

            if (!isset($parameters[$name])) {
                throw new \RuntimeException("Missing parameter for $name");
            }

            $route = str_replace('{' . $name . '}', $parameters[$name], $route);
        }

        return $route;
    }

    public function getSlugs(): array
    {
        if (preg_match_all('/\{(\w+)\}/', $this->route, $matches, PREG_SET_ORDER)) {
            return array_map(function ($match) {
                return $match[1];
            }, $matches);
        }

        return [];
    }

    private function compile(): string
    {
        if ($this->compiled === null) {

            $compiled = preg_quote($this->route, '/');

            foreach ($this->getSlugs() as $name) {

                $slug = '\\{' . $name . '\\}';
                $requirement = $this->requirements[$name] ?? '[^\/]+';
                $replacement = isset($this->defaults[$name])
                    ? "(?:\/($requirement))?"
                    : "\/($requirement)";
                    $compiled = str_replace("\/$slug", $replacement, $compiled);

                $this->parameters[] = [
                    'name' => $name,
                    'value' => $this->defaults[$name] ?? null
                ];
            }

            $this->compiled = $compiled;
        }

        return $this->compiled;
    }
}
