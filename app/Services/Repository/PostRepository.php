<?php

declare(strict_types=1);

namespace App\Services\Repository;

use App\Entities\GroupInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class PostRepository extends EntityRepository implements PostRepositoryInterface
{
    public function findAllWithOffset(int $offset = 0): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.group IS NULL')
            ->andWhere('o.refusal IS NULL')
            ->andWhere('o.anonymous = :anonymous')
            ->orWhere('o.approvedBy IS NOT NULL')
            ->andWhere('o.refusal IS NULL')
            ->andWhere('o.group IS NULL')
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

    private function createRefusedQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.refusal', 'refusal')
            ->addOrderBy('refusal.createdAt', 'DESC');
    }

    public function findAllUnapprovedForGroups(Collection $groups): array
    {
        $qb = $this->createUnapprovedQueryBuilder();

        return $qb
            ->andWhere('o.group IN (:groups)')
            ->setParameter('groups', $groups)
            ->getQuery()
            ->getResult();
    }

    public function findAllUnapproved(): array
    {
        return $this->createUnapprovedQueryBuilder()
            ->getQuery()
            ->getResult();
    }

    public function findAllRefusedForGroups(Collection $groups): array
    {
        $qb = $this->createRefusedQueryBuilder();

        return $qb
            ->andWhere('o.group IN (:groups)')
            ->setParameter('groups', $groups)
            ->getQuery()
            ->getResult();
    }

    public function findAllRefused(): array
    {
        return $this->createRefusedQueryBuilder()
            ->getQuery()
            ->getResult();
    }

    public function countRefused(): int
    {
        return (int)$this->createRefusedQueryBuilder()
            ->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countRefusedForGroups(Collection $groups): int
    {
        $qb = $this->createRefusedQueryBuilder();

        return (int)$qb
            ->andWhere('o.group IN (:groups)')
            ->setParameter('groups', $groups)
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

    public function countUnapprovedForGroups(Collection $groups): int
    {
        $qb = $this->createUnapprovedQueryBuilder();

        return (int)$qb
            ->andWhere('o.group IN (:groups)')
            ->setParameter('groups', $groups)
            ->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findAllForGroup(GroupInterface $group, int $offset = 0): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.group = :group')
            ->andWhere('o.refusal IS NULL')
            ->andWhere('o.anonymous = :anonymous')
            ->orWhere('o.approvedBy IS NOT NULL')
            ->andWhere('o.refusal IS NULL')
            ->andWhere('o.group = :group')
            ->setParameter('anonymous', false)
            ->setParameter('group', $group)
            ->setFirstResult($offset)
            ->setMaxResults(20)
            ->addOrderBy('o.approvedAt', 'DESC')
            ->addOrderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
