<?php

declare(strict_types=1);

namespace App\Services\Factory;

use App\Entities\Comment;
use App\Entities\CommentInterface;
use App\Entities\PostInterface;
use App\Entities\UserInterface;

class CommentFactory implements CommentFactoryInterface
{
    public function createWithUserPostAndComment(
        UserInterface $user,
        PostInterface $post,
        ?CommentInterface $replyTo
    ): CommentInterface
    {
        $comment = new Comment();

        $comment->setUser($user);
        $comment->setPost($post);

        if (isset($replyTo)) {
            $replyTo->addReply($comment);
        }

        return $comment;
    }
}
