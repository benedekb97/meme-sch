<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\GroupAwareTrait;
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
    use GroupAwareTrait;

    private Collection $votes;

    private Collection $comments;

    private bool $anonymous = false;

    private ?UserInterface $approvedBy = null;

    private ?DateTimeInterface $approvedAt = null;

    private ?RefusalInterface $refusal = null;

    private Collection $refusals;

    private ?ImageInterface $image = null;

    private Collection $images;

    private Collection $reports;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->refusals = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->reports = new ArrayCollection();
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
        $collection = $this->comments->filter(
            static function (CommentInterface $comment): bool
            {
                return !$comment->hasReplyTo();
            }
        );

        $iterator = $collection->getIterator();

        $iterator->uasort(
            static function (CommentInterface $a, CommentInterface $b): int
            {
                return ($b->getUpvoteCount() - $b->getDownvoteCount()) <=> ($a->getUpvoteCount() - $a->getDownvoteCount());
            }
        );

        return new ArrayCollection(iterator_to_array($iterator));
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

    public function getStatus(): string
    {
        if ($this->hasActiveRefusal()) {
            return self::STATUS_REFUSED;
        }

        if ($this->isAnonymous() && !$this->isApproved()) {
            return self::STATUS_AWAITING_APPROVAL;
        }

        return self::STATUS_APPROVED;
    }

    public function getPostStyle(): string
    {
        return self::STATUS_STYLE_MAP[$this->getStatus()] ?? self::STYLE_APPROVED;
    }

    public function getRefusal(): ?RefusalInterface
    {
        return $this->refusal;
    }

    public function setRefusal(?RefusalInterface $refusal): void
    {
        $this->refusal = $refusal;
    }

    public function hasActiveRefusal(): bool
    {
        return isset($this->refusal);
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
            $refusal->setPost($this);
        }
    }

    public function removeRefusal(RefusalInterface $refusal): void
    {
        if ($this->hasRefusal($refusal)) {
            $this->refusals->removeElement($refusal);
            $refusal->setPost(null);
        }
    }

    public function getImage(): ?ImageInterface
    {
        return $this->image;
    }

    public function setImage(?ImageInterface $image): void
    {
        $this->image = $image;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function hasImage(ImageInterface $image): bool
    {
        return $this->images->contains($image);
    }

    public function addImage(ImageInterface $image): void
    {
        if (!$this->hasImage($image)) {
            $this->images->add($image);
            $image->setPost($this);
        }
    }

    public function removeImage(ImageInterface $image): void
    {
        if ($this->hasImage($image)) {
            $this->images->removeElement($image);
            $image->setPost(null);
        }
    }

    public function hasReportByUser(UserInterface $user): bool
    {
        return !$this->reports
            ->filter(
                static function (ReportInterface $report) use ($user): bool
                {
                    return $report->getUser() === $user;
                }
            )
            ->isEmpty();
    }

    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function hasReport(ReportInterface $report): bool
    {
        return $this->reports->contains($report);
    }

    public function addReport(ReportInterface $report): void
    {
        if (!$this->hasReport($report)) {
            $this->reports->add($report);
            $report->setPost($this);
        }
    }

    public function removeReport(ReportInterface $report): void
    {
        if ($this->hasReport($report)) {
            $this->reports->removeElement($report);
            $report->setPost(null);
        }
    }
}
