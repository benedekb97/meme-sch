<?php

declare(strict_types=1);

namespace App\Entities;

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
    VoteableInterface
{
    public function getFilePath(): ?string;

    public function setFilePath(?string $filePath): void;

    public function getComments(): Collection;

    public function isAnonymous(): bool;

    public function setAnonymous(bool $anonymous): void;

    public function getApprovedBy(): ?UserInterface;

    public function setApprovedBy(?UserInterface $approvedBy): void;

    public function isApproved(): bool;

    public function getApprovedAt(): ?DateTimeInterface;

    public function setApprovedAtNow(): void;
}
