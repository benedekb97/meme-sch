<?php

declare(strict_types=1);

namespace App\Services\Repository;

use App\Entities\UserInterface;
use Doctrine\Persistence\ObjectRepository;

interface UserRepositoryInterface extends ObjectRepository
{
    public function findOneByEmail(?string $email): ?UserInterface;
}
