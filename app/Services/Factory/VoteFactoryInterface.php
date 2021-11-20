<?php

declare(strict_types=1);

namespace App\Services\Factory;

use App\Entities\UserInterface;
use App\Entities\VoteableInterface;
use App\Entities\VoteInterface;

interface VoteFactoryInterface
{
    public function create(): VoteInterface;

    public function createForUserAndVoteable(UserInterface $user, VoteableInterface $voteable): VoteInterface;
}
