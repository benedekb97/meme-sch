<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\PostAwareInterface;
use App\Entities\Traits\ResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use App\Entities\Traits\UserAwareInterface;

interface ReportInterface extends ResourceInterface, TimestampableInterface, PostAwareInterface, UserAwareInterface
{
    public const REASON_CHILD_PORNOGRAPHY = 'child-pornography';
    public const REASON_HATE_SPEECH = 'hate-speech';
    public const REASON_PERSONAL = 'personal';
    public const REASON_OTHER = 'other';

    public const REASON_MAP = [
        'Gyermekpornográfia' => self::REASON_CHILD_PORNOGRAPHY,
        'Gyűlöletkeltő' => self::REASON_HATE_SPEECH,
        'Személyeskedő velem szemben' => self::REASON_PERSONAL,
        'Egyéb' => self::REASON_OTHER,
    ];

    public const STATUS_AWAITING_JUDGEMENT = 'awaiting-judgement';
    public const STATUS_EXPEDITED = 'expedited';
    public const STATUS_DISMISSED = 'dismissed';

    public const STATUS_MAP = [
        'Bírálatra vár' => self::STATUS_AWAITING_JUDGEMENT,
        'Véglegesítve' => self::STATUS_EXPEDITED,
        'Mellőzve' => self::STATUS_DISMISSED,
    ];

    public function getReason(): ?string;

    public function setReason(?string $reason): void;

    public function getStatus(): ?string;

    public function setStatus(?string $status): void;
}
