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

    private bool $administrator = false;

    private Collection $refusals;

    private ?ImageInterface $profilePicture = null;

    private Collection $groupUsers;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->refusals = new ArrayCollection();
        $this->groupUsers = new ArrayCollection();
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

    public function isAdministrator(): bool
    {
        return $this->administrator;
    }

    public function setAdministrator(bool $administrator): void
    {
        $this->administrator = $administrator;
    }

    public function getRefusals(): Collection
    {
        return $this->refusals;
    }

    public function hasRefusal(RefusalInterface $refusal): bool
    {
        return $this->refusals->contains($refusal);
    }

    public function addRefusal(RefusalInterface $refusal): void
    {
        if (!$this->hasRefusal($refusal)) {
            $this->refusals->add($refusal);
            $refusal->setUser($this);
        }
    }

    public function removeRefusal(RefusalInterface $refusal): void
    {
        if ($this->hasRefusal($refusal)) {
            $this->refusals->removeElement($refusal);
            $refusal->setUser(null);
        }
    }

    public function getProfilePicture(): ?ImageInterface
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?ImageInterface $profilePicture): void
    {
        $this->profilePicture = $profilePicture;
    }

    public function getGroupUsers(): Collection
    {
        return $this->groupUsers;
    }

    public function hasGroupUser(GroupUserInterface $groupUser): bool
    {
        return $this->groupUsers->contains($groupUser);
    }

    public function addGroupUser(GroupUserInterface $groupUser): void
    {
        if (!$this->hasGroupUser($groupUser)) {
            $this->groupUsers->add($groupUser);
            $groupUser->setUser($this);
        }
    }

    public function removeGroupUser(GroupUserInterface $groupUser): void
    {
        if ($this->hasGroupUser($groupUser)) {
            $this->groupUsers->removeElement($groupUser);
            $groupUser->setUser(null);
        }
    }

    public function getGroups(): Collection
    {
        return $this->groupUsers->map(fn (GroupUserInterface $gu) => $gu->getGroup());
    }

    public function hasGroup(GroupInterface $group): bool
    {
        return $this->getGroups()->contains($group);
    }

    public function isGroupLeader(): bool
    {
        return !$this->groupUsers->filter(
            static function (GroupUserInterface $groupUser): bool
            {
                return $groupUser->getStatus() === GroupUserInterface::STATUS_LEADER;
            }
        )->isEmpty();
    }

    public function canPostInGroup(GroupInterface $group): bool
    {
        $groupUser = $this->groupUsers->filter(
            static function (GroupUserInterface $groupUser) use ($group): bool
            {
                return $groupUser->isLeader();
            }
        )->first();

        if (!isset($groupUser)) {
            return false;
        }

        return $groupUser->canPost();
    }

    public function getGroupsWithLeadership(): Collection
    {
        return $this->groupUsers->filter(
            static function (GroupUserInterface $groupUser): bool
            {
                return $groupUser->isLeader();
            }
        )->map(
            static function (GroupUserInterface $groupUser): GroupInterface
            {
                return $groupUser->getGroup();
            }
        );
    }

    public function getSortedPosts(): Collection
    {
        $iterator = $this->posts->getIterator();

        $iterator->uasort(
            static function (PostInterface $a, PostInterface $b): int
            {
                return ($b->getApprovedAt() ?? $b->getCreatedAt()) <=> ($a->getApprovedAt() ?? $a->getCreatedAt());
            }
        );

        return new ArrayCollection(iterator_to_array($iterator));
    }
}
