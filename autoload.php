<?php

final class Autoloader
{
    public function __construct(
        private array $map = []
    ) {
        if (!spl_autoload_register([$this, 'load'])) {
            throw new \RuntimeException('Cannot register autoloader');
        }
    }
    
    public function load(string $class): void
    {
        foreach ($this->map as $namespace => $directory) {

            $namespace = trim($namespace, '\\');

            if (0 !== strpos($class, $namespace)) {
                continue;
            }

            $file = $directory . str_replace('\\', '/', substr($class, strlen($namespace))) . '.php';
            file_exists($file) && require_once $file;

        }
    }
}