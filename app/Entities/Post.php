<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\NameableTrait;
use App\Entities\Traits\ResourceTrait;
use App\Entities\Traits\TimestampableTrait;
use App\Entities\Traits\UserAwareTrait;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Post implements PostInterface
{
    use ResourceTrait;
    use TimestampableTrait;
    use NameableTrait;
    use UserAwareTrait;

    private ?string $filePath = null;

    private Collection $votes;

    private Collection $comments;

    private bool $anonymous = false;

    private ?UserInterface $approvedBy = null;

    private ?DateTimeInterface $approvedAt = null;

    private ?DateTimeInterface $deletedAt = null;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): void
    {
        $this->filePath = $filePath;
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
            $vote->setVoteable($this);
        }
    }

    public function removeVote(VoteInterface $vote): void
    {
        if ($this->hasVote($vote)) {
            $this->votes->removeElement($vote);
            $vote->setVoteable(null);
        }
    }

    public function getUpvoteCount(): int
    {
        return $this->votes->filter(
            static function (VoteInterface $vote): bool
            {
                return $vote->getType() === VoteInterface::TYPE_UP;
            }
        )->count();
    }

    public function getDownvoteCount(): int
    {
        return $this->votes->filter(
            static function (VoteInterface $vote): bool
            {
                return $vote->getType() === VoteInterface::TYPE_DOWN;
            }
        )->count();
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function isAnonymous(): bool
    {
        return $this->anonymous;
    }

    public function setAnonymous(bool $anonymous): void
    {
        $this->anonymous = $anonymous;
    }

    public function getApprovedBy(): ?UserInterface
    {
        return $this->approvedBy;
    }

    public function setApprovedBy(?UserInterface $approvedBy): void
    {
        $this->approvedBy = $approvedBy;

        if ($approvedBy === null) {
            $this->setApprovedAt(null);
        }
    }

    public function isApproved(): bool
    {
        return isset($this->approvedBy);
    }

    private function setApprovedAt(?DateTimeInterface $approvedAt): void
    {
        $this->approvedAt = $approvedAt;
    }

    public function getApprovedAt(): ?DateTimeInterface
    {
        return $this->approvedAt;
    }

    public function setApprovedAtNow(): void
    {
        $this->approvedAt = new DateTime();
    }

    public function isDeleted(): bool
    {
        return isset($this->deletedAt);
    }

    public function delete(): void
    {
        $this->deletedAt = new DateTime();
    }

    public function restore(): void
    {
        $this->deletedAt = null;
    }
}
