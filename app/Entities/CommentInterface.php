<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\PostAwareInterface;
use App\Entities\Traits\ResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use App\Entities\Traits\UserAwareInterface;
use Doctrine\Common\Collections\Collection;

interface CommentInterface extends
    ResourceInterface,
    TimestampableInterface,
    UserAwareInterface,
    PostAwareInterface,
    VoteableInterface
{
    public function getComment(): ?string;

    public function setComment(?string $comment): void;

    public function getReplyTo(): ?CommentInterface;

    public function hasReplyTo(): bool;

    public function setReplyTo(?CommentInterface $comment): void;

    public function getReplies(): Collection;

    public function hasReplies(): bool;

    public function hasReply(CommentInterface $comment): bool;

    public function addReply(CommentInterface $comment): void;

    public function removeReply(CommentInterface $comment): void;

    public function getReplyLevel(): int;
}
