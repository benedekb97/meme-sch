<?php

declare(strict_types=1);

namespace App\Entities\Traits;

use App\Entities\PostInterface;

interface PostAwareInterface
{
    public function getPost(): ?PostInterface;

    public function setPost(?PostInterface $post): void;

    public function hasPost(): bool;
}
