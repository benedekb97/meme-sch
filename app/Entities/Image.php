<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\NameableTrait;
use App\Entities\Traits\PostAwareTrait;
use App\Entities\Traits\ResourceTrait;
use App\Entities\Traits\TimestampableTrait;

class Image implements ImageInterface
{
    use ResourceTrait;
    use TimestampableTrait;
    use NameableTrait;
    use PostAwareTrait;

    private ?string $filePath = null;

    private ?array $sourceSet = null;

    private bool $convertable = false;

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): void
    {
        $this->filePath = $filePath;
    }

    public function getSourceSet(): ?array
    {
        return $this->sourceSet;
    }

    public function setSourceSet(?array $sourceSet): void
    {
        $this->sourceSet = $sourceSet;
    }

    public function hasSourceSet(): bool
    {
        return isset($this->sourceSet);
    }

    public function isConvertable(): bool
    {
        return $this->convertable;
    }

    public function setConvertable(bool $convertable): void
    {
        $this->convertable = $convertable;
    }
}
