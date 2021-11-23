<?php

declare(strict_types=1);

namespace App\Http\Api\Entity;

use App\Entities\GroupInterface;

interface ProfileInterface
{
    public function setInternalId(?string $internalId): void;

    public function getInternalId(): ?string;

    public function setDisplayName(?string $displayName): void;

    public function getDisplayName(): ?string;

    public function setSurname(?string $surname): void;

    public function getSurname(): ?string;

    public function setGivenNames(?string $givenNames): void;

    public function getGivenNames(): ?string;

    public function setEmailAddress(?string $emailAddress): void;

    public function getEmailAddress(): ?string;

    public function getGroups(): ?array;

    public function addGroup(string $status, GroupInterface $group): void;

    public function hasGroup(GroupInterface $group): bool;
}
