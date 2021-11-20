<?php

declare(strict_types=1);

namespace App\Http\Api\Entity;

class Profile implements ProfileInterface
{
    private ?string $internalId = null;

    private ?string $displayName = null;

    private ?string $surname = null;

    private ?string $givenNames = null;

    private ?string $emailAddress = null;

    public function setInternalId(?string $internalId): void
    {
        $this->internalId = $internalId;
    }

    public function getInternalId(): ?string
    {
        return $this->internalId;
    }

    public function setDisplayName(?string $displayName): void
    {
        $this->displayName = $displayName;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setGivenNames(?string $givenNames): void
    {
        $this->givenNames = $givenNames;
    }

    public function getGivenNames(): ?string
    {
        return $this->givenNames;
    }

    public function setEmailAddress(?string $emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }
}
