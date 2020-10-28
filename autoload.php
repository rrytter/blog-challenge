<?php

final class Autoloader
{
    private array $map;

    public function __construct(array $map = [])
    {
        $this->map = $map;

        if (!spl_autoload_register([$this, 'load'])) {
            throw new \RuntimeException('Cannot register autoloader.');
        }
    }
    
    public function load(string $class): void
    {
        foreach ($this->map as $namespace => $directory) {

            if (0 !== strpos($class, trim($namespace, '\\'))) {
                continue;
            }

            $file = $directory . str_replace('\\', '/', substr($class, strlen(trim($namespace, '\\')))) . '.php';
            file_exists($file) && require_once $file;

        }
    }
}