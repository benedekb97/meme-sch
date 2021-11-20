<?php

declare(strict_types=1);

namespace App\Services\Repository;

use App\Entities\UserInterface;
use App\Entities\VoteableInterface;
use App\Entities\VoteInterface;

interface VoteRepositoryInterface
{
    public function findByUserAndVoteable(UserInterface $user, VoteableInterface $voteable): ?VoteInterface;
}
