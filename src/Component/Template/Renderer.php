<?php

namespace App\Component\Template;

use App\Component\Routing\Router;
use SplFileInfo;

class Renderer
{
    public function __construct(
        private Router $router
    ) {}

    private function getRouter(): Router
    {
        return $this->router;
    }

    public function generate(string $name, array $parameters = []): ?string
    {
        return $this->getRouter()->generate($name, $parameters);
    }

    public function render(SplFileInfo $file, array $variables = []): string
    {
        ob_start();

        (function($__variables, $__file) {
            extract($__variables);
            include $__file;
        })($variables, $file);
        
        return ob_get_clean();
    }

    // private function loadManifest(): array
    // {
    //     $manifest = "{$this->getKernel()->getProjectDir()}/public/build/manifest.json";
    //     return json_decode(file_get_contents($manifest), true);
    // }
}