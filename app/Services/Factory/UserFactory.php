<?php

declare(strict_types=1);

namespace App\Services\Factory;

use App\Entities\User;
use App\Entities\UserInterface;
use App\Http\Api\Entity\ProfileInterface;

class UserFactory implements UserFactoryInterface
{
    public function createFromAuthSchProfile(ProfileInterface $profile): UserInterface
    {
        $user = new User();

        $user->setEmail($profile->getEmailAddress());
        $user->setAuthSchInternalId($profile->getInternalId());
        $user->setName($profile->getDisplayName());

        return $user;
    }
}
