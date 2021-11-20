<?php

declare(strict_types=1);

namespace App\Entities;

interface ResourceInterface
{
    public function getId(): ?int;

    public function setId(?int $id): void;
}
