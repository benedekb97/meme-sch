<?php

declare(strict_types=1);

namespace App\Entities\Traits;

use App\Entities\UserInterface;

interface UserAwareInterface
{
    public function getUser(): ?UserInterface;

    public function setUser(?UserInterface $user): void;

    public function hasUser(): bool;
}
