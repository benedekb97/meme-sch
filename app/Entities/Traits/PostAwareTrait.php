<?php

declare(strict_types=1);

namespace App\Entities\Traits;

use App\Entities\PostInterface;

trait PostAwareTrait
{
    private ?PostInterface $post = null;

    public function getPost(): ?PostInterface
    {
        return $this->post;
    }

    public function setPost(?PostInterface $post): void
    {
        $this->post = $post;
    }

    public function hasPost(): bool
    {
        return isset($this->post);
    }
}
