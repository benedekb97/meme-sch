<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\ResourceTrait;
use App\Entities\Traits\TimestampableTrait;
use App\Entities\Traits\UserAwareTrait;

class GroupUser implements GroupUserInterface
{
    use ResourceTrait;
    use TimestampableTrait;
    use UserAwareTrait;

    private ?GroupInterface $group = null;

    private ?string $status = null;

    public function getGroup(): ?GroupInterface
    {
        return $this->group;
    }

    public function setGroup(?GroupInterface $group): void
    {
        $this->group = $group;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function canPost(): bool
    {
        return true;
    }

    public function isLeader(): bool
    {
        return $this->status === self::STATUS_LEADER;
    }
}
