<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\PostAwareInterface;
use App\Entities\Traits\ResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use App\Entities\Traits\UserAwareInterface;

interface CommentInterface extends
    ResourceInterface,
    TimestampableInterface,
    UserAwareInterface,
    PostAwareInterface,
    VoteableInterface
{
    public function getComment(): ?string;

    public function setComment(?string $comment): void;
}
