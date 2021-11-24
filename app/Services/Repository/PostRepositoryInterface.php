<?php

declare(strict_types=1);

namespace App\Services\Repository;

use App\Entities\GroupInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ObjectRepository;

interface PostRepositoryInterface extends ObjectRepository
{
    public function searchByGroups(Collection $groups, string $term): array;

    public function findAllWithOffset(int $offset = 0): array;

    public function findAllUnapprovedForGroups(Collection $groups): array;

    public function findAllUnapproved(): array;

    public function findAllRefusedForGroups(Collection $groups): array;

    public function findAllRefused(): array;

    public function countRefused(): int;

    public function countRefusedForGroups(Collection $groups): int;

    public function countUnapproved(): int;

    public function countUnapprovedForGroups(Collection $groups): int;

    public function findAllForGroup(GroupInterface $group, int $offset = 0): array;
}
