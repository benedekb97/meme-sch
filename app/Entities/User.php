<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\NameableTrait;
use App\Entities\Traits\ResourceTrait;
use App\Entities\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class User implements UserInterface
{
    use ResourceTrait;
    use TimestampableTrait;
    use NameableTrait;

    private ?string $password = null;

    private ?string $email;

    private ?string $authSchInternalId = null;

    private ?string $nickName = null;

    private Collection $posts;

    private Collection $votes;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function getAuthIdentifier(): ?int
    {
        return $this->id;
    }

    public function getAuthPassword(): ?string
    {
        return $this->password;
    }

    public function getRememberToken(): ?string
    {
        return null;
    }

    public function setRememberToken($value): void
    {
        return;
    }

    public function getRememberTokenName(): ?string
    {
        return null;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getAuthSchInternalId(): ?string
    {
        return $this->authSchInternalId;
    }

    public function setAuthSchInternalId(?string $authSchInternalId): void
    {
        $this->authSchInternalId = $authSchInternalId;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function hasPost(PostInterface $post): bool
    {
        return $this->posts->contains($post);
    }

    public function addPost(PostInterface $post): void
    {
        if (!$this->hasPost($post)) {
            $this->posts->add($post);
            $post->setUser($this);
        }
    }

    public function removePost(PostInterface $post): void
    {
        if ($this->hasPost($post)) {
            $this->posts->removeElement($post);
            $post->setUser(null);
        }
    }

    public function getNickName(): ?string
    {
        return $this->nickName;
    }

    public function setNickName(?string $nickName): void
    {
        $this->nickName = $nickName;
    }

    public function hasNickName(): bool
    {
        return isset($this->nickName);
    }

    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function hasVote(VoteInterface $vote): bool
    {
        return $this->votes->contains($vote);
    }

    public function addVote(VoteInterface $vote): void
    {
        if (!$this->hasVote($vote)) {
            $this->votes->add($vote);
            $vote->setUser($this);
        }
    }

    public function removeVote(VoteInterface $vote): void
    {
        if ($this->hasVote($vote)) {
            $this->votes->removeElement($vote);
            $vote->setUser(null);
        }
    }

    public function hasUpvoted(VoteableInterface $voteable): bool
    {
        return $this->votes->filter(
            static function (VoteInterface $vote) use ($voteable): bool
            {
                return $vote->getVoteable() === $voteable && $vote->getType() === VoteInterface::TYPE_UP;
            }
        )->count() > 0;
    }

    public function hasDownvoted(VoteableInterface $voteable): bool
    {
        return $this->votes->filter(
            static function (VoteInterface $vote) use ($voteable): bool
            {
                return $vote->getVoteable() === $voteable && $vote->getType() === VoteInterface::TYPE_DOWN;
            }
        )->count() > 0;
    }
}
