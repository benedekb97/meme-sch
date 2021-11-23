<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\NameableInterface;
use App\Entities\Traits\ResourceInterface;
use App\Entities\Traits\TimestampableInterface;

interface ImageInterface extends
    ResourceInterface,
    TimestampableInterface,
    NameableInterface
{
    public function getFilePath(): ?string;

    public function setFilePath(?string $filePath): void;
}
