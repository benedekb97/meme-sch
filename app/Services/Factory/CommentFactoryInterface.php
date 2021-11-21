<?php

declare(strict_types=1);

namespace App\Services\Factory;

use App\Entities\CommentInterface;
use App\Entities\PostInterface;
use App\Entities\UserInterface;

interface CommentFactoryInterface
{
    public function createWithUserPostAndComment(
        UserInterface $user,
        PostInterface $post,
        ?CommentInterface $replyTo
    ): CommentInterface;
}
