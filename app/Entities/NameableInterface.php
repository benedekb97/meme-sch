<?php

declare(strict_types=1);

namespace App\Entities;

interface NameableInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    public function hasName(): bool;
}
