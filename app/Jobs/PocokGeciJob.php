<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class PocokGeciJob
{
    private UserRepositoryInterface $userRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        UserRepositoryInterface $userRepository,
        EntityManager $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke()
    {
        $pocok = $this->userRepository->findOneByEmail('adam.torok96@gmail.com');

        $pocok->setNickName('pocokgeci69');

        $this->entityManager->persist($pocok);
        $this->entityManager->flush();
    }
}
