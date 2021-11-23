<?php

declare(strict_types=1);

namespace App\Services\Provider;

use App\Entities\GroupInterface;
use App\Services\Factory\GroupFactoryInterface;
use App\Services\Repository\GroupRepositoryInterface;

class GroupProvider implements GroupProviderInterface
{
    private GroupRepositoryInterface $groupRepository;

    private GroupFactoryInterface $groupFactory;

    public function __construct(
        GroupRepositoryInterface $groupRepository,
        GroupFactoryInterface $groupFactory
    )
    {
        $this->groupRepository = $groupRepository;
        $this->groupFactory = $groupFactory;
    }

    public function provide(int $id): GroupInterface
    {
        return $this->groupRepository->find($id) ?? $this->groupFactory->createWithId($id);
    }
}
