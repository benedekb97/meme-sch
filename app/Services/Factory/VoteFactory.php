<?php

declare(strict_types=1);

namespace App\Services\Factory;

use App\Entities\UserInterface;
use App\Entities\Vote;
use App\Entities\VoteableInterface;
use App\Entities\VoteInterface;

class VoteFactory implements VoteFactoryInterface
{
    public function create(): VoteInterface
    {
        return new Vote();
    }

    public function createForUserAndVoteable(UserInterface $user, VoteableInterface $voteable): VoteInterface
    {
        $vote = $this->create();

        $user->addVote($vote);
        $voteable->addVote($vote);

        return $vote;
    }
}
