<?php

declare(strict_types=1);

namespace App\Services\Factory;

use App\Entities\GroupInterface;
use App\Entities\GroupUserInterface;
use App\Entities\UserInterface;

interface GroupUserFactoryInterface
{
    public function createWithGroupUserAndStatus(
        GroupInterface $group,
        UserInterface $user,
        string $status
    ): GroupUserInterface;
}
