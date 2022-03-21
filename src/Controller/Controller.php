<?php

namespace App\Controller;

use App\Component\Kernel\Kernel;
use App\Component\Template\Renderer;
use SplFileInfo;

abstract class Controller
{
    private Renderer $renderer;

    public function __construct(
        private Kernel $kernel
    ) {
        $this->renderer = new Renderer($this->getKernel()->getRouter());
    }

    protected function getKernel(): Kernel
    {
        return $this->kernel;
    }

    private function getRenderer(): Renderer
    {
        return $this->renderer;
    }

    protected function render(string $template, array $variables = []): string
    {
        return $this->getRenderer()->render(
            new SplFileInfo("{$this->getKernel()->getProjectDir()}/templates/$template"),
            $variables
        );
    }
}