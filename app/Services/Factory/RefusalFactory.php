<?php

declare(strict_types=1);

namespace App\Services\Factory;

use App\Entities\PostInterface;
use App\Entities\Refusal;
use App\Entities\RefusalInterface;
use App\Entities\UserInterface;

class RefusalFactory implements RefusalFactoryInterface
{
    public function createForPostByUser(PostInterface $post, UserInterface $user): RefusalInterface
    {
        $refusal = new Refusal();

        $post->addRefusal($refusal);
        $post->setRefusal($refusal);

        $user->addRefusal($refusal);

        return $refusal;
    }
}
