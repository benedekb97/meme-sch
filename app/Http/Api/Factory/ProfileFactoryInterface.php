<?php

declare(strict_types=1);

namespace App\Http\Api\Factory;

use App\Http\Api\Entity\ProfileInterface;

interface ProfileFactoryInterface
{
    public function createFromAuthSchResponse(array $response): ProfileInterface;
}
