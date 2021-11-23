<?php

declare(strict_types=1);

namespace App\Services\Repository;

use App\Entities\GroupInterface;
use Doctrine\Persistence\ObjectRepository;

interface PostRepositoryInterface extends ObjectRepository
{
    public function findAllWithOffset(int $offset = 0): array;

    public function findAllUnapproved(): array;

    public function findAllRefused(): array;

    public function countRefused(): int;

    public function countUnapproved(): int;

    public function findAllForGroup(GroupInterface $group, int $offset = 0): array;
}
