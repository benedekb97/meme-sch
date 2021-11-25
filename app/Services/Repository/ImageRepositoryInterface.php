<?php

declare(strict_types=1);

namespace App\Services\Repository;

use Doctrine\Persistence\ObjectRepository;

interface ImageRepositoryInterface extends ObjectRepository
{
    public function findAllNonConverted(): array;
}
