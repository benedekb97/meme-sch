<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\PostAwareTrait;
use App\Entities\Traits\ResourceTrait;
use App\Entities\Traits\TimestampableTrait;
use App\Entities\Traits\UserAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Comment implements CommentInterface
{
    use ResourceTrait;
    use TimestampableTrait;
    use UserAwareTrait;
    use PostAwareTrait;

    private Collection $votes;

    private ?string $comment = null;

    private ?CommentInterface $replyTo = null;

    private Collection $replies;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
        $this->replies = new ArrayCollection();
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getReplyTo(): ?CommentInterface
    {
        return $this->replyTo;
    }

    public function hasReplyTo(): bool
    {
        return isset($this->replyTo);
    }

    public function setReplyTo(?CommentInterface $comment): void
    {
        $this->replyTo = $comment;
    }

    public function getReplies(): Collection
    {
        $collection = $this->replies;

        $iterator = $collection->getIterator();

        $iterator->uasort(
            static function (CommentInterface $a, CommentInterface $b): int
            {
                return ($b->getUpvoteCount() - $b->getDownvoteCount()) <=> ($a->getUpvoteCount() - $a->getDownvoteCount());
            }
        );

        return new ArrayCollection(iterator_to_array($iterator));
    }

    public function hasReplies(): bool
    {
        return !$this->replies->isEmpty();
    }

    public function hasReply(CommentInterface $comment): bool
    {
        return $this->replies->contains($comment);
    }

    public function addReply(CommentInterface $comment): void
    {
        if (!$this->hasReply($comment)) {
            $comment->setReplyTo($this);
            $this->replies->add($comment);
        }
    }

    public function removeReply(CommentInterface $comment): void
    {
        if ($this->hasReply($comment)) {
            $comment->setReplyTo(null);
            $this->replies->removeElement($comment);
        }
    }

    public function getReplyLevel(): int
    {
        if (!$this->hasReplyTo()) {
            return 0;
        }

        return $this->getReplyTo()->getReplyLevel() + 1;
    }
}
