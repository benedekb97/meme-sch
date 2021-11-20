<?php

declare(strict_types=1);

namespace App\Services\Repository;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository implements PostRepositoryInterface
{
    public function findAllWithOffset(int $offset = 0): array
    {
        return $this->createQueryBuilder('o')
            ->setFirstResult($offset)
            ->setMaxResults(20)
            ->addOrderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
