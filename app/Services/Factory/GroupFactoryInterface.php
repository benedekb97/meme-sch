<?php

declare(strict_types=1);

namespace App\Services\Factory;

use App\Entities\GroupInterface;

interface GroupFactoryInterface
{
    public function createWithId(int $id): GroupInterface;
}
