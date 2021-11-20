<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\Common\Collections\Collection;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Entities\Traits\ResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use App\Entities\Traits\NameableInterface;

interface UserInterface extends
    Authenticatable,
    ResourceInterface,
    TimestampableInterface,
    NameableInterface
{
    public function getPassword(): ?string;

    public function setPassword(?string $password): void;

    public function getEmail(): ?string;

    public function setEmail(?string $email): void;

    public function getAuthSchInternalId(): ?string;

    public function setAuthSchInternalId(?string $authSchInternalId): void;

    public function getPosts(): Collection;

    public function hasPost(PostInterface $post): bool;

    public function addPost(PostInterface $post): void;

    public function removePost(PostInterface $post): void;

    public function getNickName(): ?string;

    public function setNickName(?string $nickName): void;

    public function hasNickName(): bool;

    public function getVotes(): Collection;

    public function hasVote(VoteInterface $vote): bool;

    public function addVote(VoteInterface $vote): void;

    public function removeVote(VoteInterface $vote): void;

    public function hasUpvoted(VoteableInterface $voteable): bool;

    public function hasDownvoted(VoteableInterface $voteable): bool;
}
