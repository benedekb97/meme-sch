<?php

declare(strict_types=1);

namespace App\Services\Factory;

use App\Entities\PostInterface;
use App\Entities\ReportInterface;
use App\Entities\UserInterface;

interface ReportFactoryInterface
{
    public function createForUserAndPost(UserInterface $user, PostInterface $post): ReportInterface;
}
