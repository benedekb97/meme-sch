<?php

declare(strict_types=1);

namespace App\Entities\Traits;

use App\Entities\UserInterface;

trait UserAwareTrait
{
    private ?UserInterface $user = null;

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function hasUser(): bool
    {
        return isset($this->user);
    }
}
