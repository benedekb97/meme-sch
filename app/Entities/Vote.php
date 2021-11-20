<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\ResourceTrait;
use App\Entities\Traits\TimestampableTrait;
use App\Entities\Traits\UserAwareTrait;
use LogicException;

class Vote implements VoteInterface
{
    use ResourceTrait;
    use TimestampableTrait;
    use UserAwareTrait;

    private ?string $type = null;

    private ?PostInterface $post = null;

    private ?CommentInterface $comment = null;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getVoteable(): ?VoteableInterface
    {
        if (isset($this->post)) {
            return $this->post;
        }

        if (isset($this->comment)) {
            return $this->comment;
        }

        return null;
    }

    public function setVoteable(?VoteableInterface $voteable): void
    {
        if ($voteable instanceof CommentInterface) {
            $this->comment = $voteable;
        }

        if ($voteable instanceof PostInterface) {
            $this->post = $voteable;
        }

        if ($voteable === null) {
            $this->comment = null;
            $this->post = null;
        }

        throw new LogicException(
            sprintf(
                'Unknown voteable type \'%s\'',
                get_class($voteable)
            )
        );
    }
}
