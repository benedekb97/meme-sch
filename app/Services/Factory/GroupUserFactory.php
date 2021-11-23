<?php

declare(strict_types=1);

namespace App\Services\Factory;

use App\Entities\GroupInterface;
use App\Entities\GroupUser;
use App\Entities\GroupUserInterface;
use App\Entities\UserInterface;

class GroupUserFactory implements GroupUserFactoryInterface
{
    public function createWithGroupUserAndStatus(
        GroupInterface $group,
        UserInterface $user,
        string $status
    ): GroupUserInterface
    {
        $groupUser = new GroupUser();

        $user->addGroupUser($groupUser);
        $group->addGroupUser($groupUser);

        $groupUser->setStatus($status);

        return $groupUser;
    }
}
