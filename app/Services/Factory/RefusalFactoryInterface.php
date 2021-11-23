<?php

declare(strict_types=1);

namespace App\Services\Factory;

use App\Entities\PostInterface;
use App\Entities\RefusalInterface;
use App\Entities\UserInterface;

interface RefusalFactoryInterface
{
    public function createForPostByUser(PostInterface $post, UserInterface $user): RefusalInterface;
}
