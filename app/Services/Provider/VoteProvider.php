<?php

declare(strict_types=1);

namespace App\Services\Provider;

use App\Entities\UserInterface;
use App\Entities\VoteableInterface;
use App\Entities\VoteInterface;
use App\Services\Factory\VoteFactoryInterface;
use App\Services\Repository\VoteRepositoryInterface;

class VoteProvider implements VoteProviderInterface
{
    private VoteRepositoryInterface $voteRepository;

    private VoteFactoryInterface $voteFactory;

    public function __construct(
        VoteRepositoryInterface $voteRepository,
        VoteFactoryInterface $voteFactory
    ) {
        $this->voteRepository = $voteRepository;
        $this->voteFactory = $voteFactory;
    }

    public function provide(int $id): VoteInterface
    {
        $vote = $this->voteRepository->find($id);

        return $vote ?? $this->voteFactory->create();
    }

    public function provideForUserAndVoteable(UserInterface $user, VoteableInterface $voteable): VoteInterface
    {
        /** @var VoteInterface $vote */
        $vote = $this->voteRepository->findByUserAndVoteable($user, $voteable);

        return $vote ?? $this->voteFactory->createForUserAndVoteable($user, $voteable);
    }
}
