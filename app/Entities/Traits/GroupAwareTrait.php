<?php

declare(strict_types=1);

namespace App\Entities\Traits;

use App\Entities\GroupInterface;

trait GroupAwareTrait
{
    private ?GroupInterface $group = null;

    public function getGroup(): ?GroupInterface
    {
        return $this->group;
    }

    public function setGroup(?GroupInterface $group): void
    {
        $this->group = $group;
    }

    public function hasGroup(): bool
    {
        return isset($this->group);
    }
}
