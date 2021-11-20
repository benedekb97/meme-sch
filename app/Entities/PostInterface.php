<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\ResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use App\Entities\Traits\NameableInterface;
use App\Entities\Traits\UserAwareInterface;

interface PostInterface extends
    ResourceInterface,
    TimestampableInterface,
    NameableInterface,
    UserAwareInterface,
    VoteableInterface
{
    public function getFilePath(): ?string;

    public function setFilePath(?string $filePath): void;
}
