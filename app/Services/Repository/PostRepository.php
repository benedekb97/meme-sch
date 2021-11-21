<?php

declare(strict_types=1);

namespace App\Services\Repository;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository implements PostRepositoryInterface
{
    public function findAllWithOffset(int $offset = 0): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.deletedAt IS NULL')
            ->andWhere('o.anonymous = :anonymous')
            ->orWhere('o.approvedBy IS NOT NULL')
            ->setParameter('anonymous', false)
            ->setFirstResult($offset)
            ->setMaxResults(20)
            ->addOrderBy('o.approvedAt', 'DESC')
            ->addOrderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllUnapproved(): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.deletedAt IS NULL')
            ->andWhere('o.anonymous = :anonymous')
            ->andWhere('o.approvedBy IS NULL')
            ->setParameter('anonymous', true)
            ->getQuery()
            ->getResult();
    }
}
