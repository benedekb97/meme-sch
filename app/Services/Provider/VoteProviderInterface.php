<?php

declare(strict_types=1);

namespace App\Services\Provider;

use App\Entities\UserInterface;
use App\Entities\VoteableInterface;
use App\Entities\VoteInterface;

interface VoteProviderInterface
{
    public function provide(int $id): VoteInterface;

    public function provideForUserAndVoteable(UserInterface $user, VoteableInterface $voteable): VoteInterface;
}
