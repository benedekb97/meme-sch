<?php

declare(strict_types=1);

namespace App\Entities\Traits;

use DateTimeInterface;

interface TimestampableInterface
{
    public function getCreatedAt(): ?DateTimeInterface;

    public function setCreatedAtNow(): void;

    public function getUpdatedAt(): ?DateTimeInterface;

    public function setUpdatedAt(?DateTimeInterface $updatedAt): void;

    public function setUpdatedAtNow(): void;
}
