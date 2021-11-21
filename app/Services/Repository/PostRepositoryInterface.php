<?php

declare(strict_types=1);

namespace App\Services\Repository;

use Doctrine\Persistence\ObjectRepository;

interface PostRepositoryInterface extends ObjectRepository
{
    public function findAllWithOffset(int $offset = 0): array;

    public function findAllUnapproved(): array;
}
