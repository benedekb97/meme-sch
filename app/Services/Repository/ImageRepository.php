<?php

declare(strict_types=1);

namespace App\Services\Repository;

use Doctrine\ORM\EntityRepository;

class ImageRepository extends EntityRepository implements ImageRepositoryInterface
{
    public function findAllNonConverted(): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.sourceSet IS NULL')
            ->andWhere('o.convertable = :convertable')
            ->setParameter('convertable', true)
            ->getQuery()
            ->getResult();
    }
}
