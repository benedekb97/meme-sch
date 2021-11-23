<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\PostAwareInterface;
use App\Entities\Traits\ResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use App\Entities\Traits\UserAwareInterface;

interface RefusalInterface extends
    ResourceInterface,
    TimestampableInterface,
    PostAwareInterface,
    UserAwareInterface
{
    public function getReason(): ?string;

    public function setReason(?string $reason): void;
}
