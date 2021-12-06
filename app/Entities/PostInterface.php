<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\GroupAwareInterface;
use App\Entities\Traits\ResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use App\Entities\Traits\NameableInterface;
use App\Entities\Traits\UserAwareInterface;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;

interface PostInterface extends
    ResourceInterface,
    TimestampableInterface,
    NameableInterface,
    UserAwareInterface,
    VoteableInterface,
    GroupAwareInterface
{
    public const STATUS_APPROVED = 'approved';
    public const STATUS_AWAITING_APPROVAL = 'awaiting_approval';
    public const STATUS_REFUSED = 'refused';

    public const STYLE_APPROVED = 'border-success';
    public const STYLE_AWAITING_APPROVAL = 'bg-warning';
    public const STYLE_REFUSED = 'bg-danger text-white';

    public const STATUS_STYLE_MAP = [
        self::STATUS_APPROVED => self::STYLE_APPROVED,
        self::STATUS_AWAITING_APPROVAL => self::STYLE_AWAITING_APPROVAL,
        self::STATUS_REFUSED => self::STYLE_REFUSED,
    ];

    public function getComments(): Collection;

    public function isAnonymous(): bool;

    public function setAnonymous(bool $anonymous): void;

    public function getApprovedBy(): ?UserInterface;

    public function setApprovedBy(?UserInterface $approvedBy): void;

    public function isApproved(): bool;

    public function getApprovedAt(): ?DateTimeInterface;

    public function setApprovedAtNow(): void;

    public function getStatus(): string;

    public function getPostStyle(): string;

    public function getRefusal(): ?RefusalInterface;

    public function setRefusal(?RefusalInterface $refusal): void;

    public function hasActiveRefusal(): bool;

    public function getRefusals(): Collection;

    public function hasRefusal(RefusalInterface $refusal): bool;

    public function addRefusal(RefusalInterface $refusal): void;

    public function removeRefusal(RefusalInterface $refusal): void;

    public function getImage(): ?ImageInterface;

    public function setImage(?ImageInterface $image): void;

    public function getImages(): Collection;

    public function hasImage(ImageInterface $image): bool;

    public function addImage(ImageInterface $image): void;

    public function removeImage(ImageInterface $image): void;

    public function hasReportByUser(UserInterface $user): bool;

    public function getReports(): Collection;

    public function hasReport(ReportInterface $report): bool;

    public function addReport(ReportInterface $report): void;

    public function removeReport(ReportInterface $report): void;
}
