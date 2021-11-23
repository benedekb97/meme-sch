<?php

declare(strict_types=1);

namespace App\Entities\Traits;

use App\Entities\GroupInterface;

interface GroupAwareInterface
{
    public function getGroup(): ?GroupInterface;

    public function setGroup(?GroupInterface $group): void;

    public function hasGroup(): bool;
}
