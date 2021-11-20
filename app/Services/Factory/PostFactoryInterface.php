<?php

declare(strict_types=1);

namespace App\Services\Factory;

use App\Entities\PostInterface;
use App\Entities\UserInterface;

interface PostFactoryInterface
{
    public function createWithUser(UserInterface $user): PostInterface;
}
