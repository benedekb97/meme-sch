<?php

declare(strict_types=1);

namespace App\Entities\Traits;

trait NameableTrait
{
    private ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function hasName(): bool
    {
        return isset($this->name);
    }
}
