<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\Common\Collections\Collection;

interface VoteableInterface
{
    public function getVotes(): Collection;

    public function hasVote(VoteInterface $vote): bool;

    public function addVote(VoteInterface $vote): void;

    public function removeVote(VoteInterface $vote): void;

    public function getUpvoteCount(): int;

    public function getDownvoteCount(): int;
}
