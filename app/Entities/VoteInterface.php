<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\ResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use App\Entities\Traits\UserAwareInterface;

interface VoteInterface extends ResourceInterface, TimestampableInterface, UserAwareInterface
{
    public const TYPE_UP = 'up';
    public const TYPE_DOWN = 'down';

    public const TYPE_MAP = [
        'Upvote' => self::TYPE_UP,
        'Downvote' => self::TYPE_DOWN,
    ];

    public function getType(): ?string;

    public function setType(?string $type): void;

    public function getVoteable(): ?VoteableInterface;

    public function setVoteable(?VoteableInterface $voteable): void;
}
