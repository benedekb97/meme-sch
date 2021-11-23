<?php

declare(strict_types=1);

namespace App\Services\Provider;

use App\Entities\GroupInterface;

interface GroupProviderInterface
{
    public function provide(int $id): GroupInterface;
}
