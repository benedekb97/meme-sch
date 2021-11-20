<?php

declare(strict_types=1);

namespace App\Services\Repository;

use App\Entities\CommentInterface;
use App\Entities\PostInterface;
use App\Entities\UserInterface;
use App\Entities\VoteableInterface;
use App\Entities\VoteInterface;
use Doctrine\ORM\EntityRepository;

class VoteRepository extends EntityRepository implements VoteRepositoryInterface
{
    public function findByUserAndVoteable(UserInterface $user, VoteableInterface $voteable): ?VoteInterface
    {
        if ($voteable instanceof PostInterface) {
            return $this->createQueryBuilder('o')
                ->andWhere('o.user = :user')
                ->andWhere('o.post = :post')
                ->setParameter('user', $user)
                ->setParameter('post', $voteable)
                ->getQuery()
                ->getOneOrNullResult();
        }

        if ($voteable instanceof CommentInterface) {
            return $this->createQueryBuilder('o')
                ->andWhere('o.user = :user')
                ->andWhere('o.comment = :comment')
                ->setParameter('user', $user)
                ->setParameter('comment', $voteable)
                ->getQuery()
                ->getOneOrNullResult();
        }

        return null;
    }
}
