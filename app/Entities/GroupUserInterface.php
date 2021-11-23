<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\ResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use App\Entities\Traits\UserAwareInterface;

interface GroupUserInterface extends
    ResourceInterface,
    TimestampableInterface,
    UserAwareInterface
{
    public const STATUS_LEADER = 'leader';
    public const STATUS_MEMBER = 'member';
    public const STATUS_ARCHIVED_MEMBER = 'archived_member';

    public const AUTH_SCH_STATUS_LEADER = 'körvezető';
    public const AUTH_SCH_STATUS_MEMBER = 'tag';
    public const AUTH_SCH_STATUS_ARCHIVED_MEMBER = 'öregtag';

    public const STATUS_AUTH_SCH_STATUS_MAP = [
        self::STATUS_LEADER => self::AUTH_SCH_STATUS_LEADER,
        self::STATUS_MEMBER => self::AUTH_SCH_STATUS_MEMBER,
        self::STATUS_ARCHIVED_MEMBER => self::AUTH_SCH_STATUS_ARCHIVED_MEMBER,
    ];

    public function getGroup(): ?GroupInterface;

    public function setGroup(?GroupInterface $group): void;

    public function getStatus(): ?string;

    public function setStatus(?string $status): void;
}
