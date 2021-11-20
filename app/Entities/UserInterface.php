<?php

declare(strict_types=1);

namespace App\Entities;

use Illuminate\Contracts\Auth\Authenticatable;

interface UserInterface extends
    Authenticatable,
    ResourceInterface,
    TimestampableInterface,
    NameableInterface
{
    public function getPassword(): ?string;

    public function setPassword(?string $password): void;

    public function getEmail(): ?string;

    public function setEmail(?string $email): void;

    public function getAuthSchInternalId(): ?string;

    public function setAuthSchInternalId(?string $authSchInternalId): void;
}
