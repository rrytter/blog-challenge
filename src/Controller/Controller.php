<?php

namespace App\Controller;

use App\Component\Kernel\Kernel;

abstract class Controller
{
    private Kernel $kernel;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    protected function getKernel(): Kernel
    {
        return $this->kernel;
    }

    protected function render(string $template, array $variables = []): string
    {
        ob_start();
        
        extract($variables);
        include "{$this->getKernel()->getProjectDir()}/templates/$template";
        
        return ob_get_clean();
    }
}