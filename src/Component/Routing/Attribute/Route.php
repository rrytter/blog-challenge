<?php

namespace App\Component\Routing\Attribute;

use Attribute;

#[Attribute]
class Route
{
    public function __construct(
        private string $path,
        private ?string $name = null,
        private array $defaults = [],
        private array $requirements = []
    ) {}

    public function getPath(): string
    {
        return $this->path;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDefaults(): array
    {
        return $this->defaults;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }
}