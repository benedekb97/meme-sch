<?php

declare(strict_types=1);

namespace App\Services\Factory;

use App\Entities\Post;
use App\Entities\PostInterface;
use App\Entities\UserInterface;

class PostFactory implements PostFactoryInterface
{
    public function createWithUser(UserInterface $user): PostInterface
    {
        $post = new Post();

        $user->addPost($post);

        return $post;
    }
}
