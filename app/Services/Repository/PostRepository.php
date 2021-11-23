<?php

declare(strict_types=1);

namespace App\Services\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class PostRepository extends EntityRepository implements PostRepositoryInterface
{
    public function findAllWithOffset(int $offset = 0): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.refusal IS NULL')
            ->andWhere('o.anonymous = :anonymous')
            ->orWhere('o.approvedBy IS NOT NULL')
            ->andWhere('o.refusal IS NULL')
            ->setParameter('anonymous', false)
            ->setFirstResult($offset)
            ->setMaxResults(20)
            ->addOrderBy('o.approvedAt', 'DESC')
            ->addOrderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    private function createUnapprovedQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.refusal IS NULL')
            ->andWhere('o.anonymous = :anonymous')
            ->andWhere('o.approvedBy IS NULL')
            ->setParameter('anonymous', true);
    }

    private function createDeletedQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.refusal', 'refusal')
            ->addOrderBy('refusal.createdAt', 'DESC');
    }

    public function findAllUnapproved(): array
    {
        return $this->createUnapprovedQueryBuilder()
            ->getQuery()
            ->getResult();
    }

    public function findAllRefused(): array
    {
        return $this->createDeletedQueryBuilder()
            ->getQuery()
            ->getResult();
    }

    public function countRefused(): int
    {
        return (int)$this->createDeletedQueryBuilder()
            ->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countUnapproved(): int
    {
        return (int)$this->createUnapprovedQueryBuilder()
            ->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
