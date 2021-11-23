<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\NameableInterface;
use App\Entities\Traits\ResourceInterface;
use App\Entities\Traits\TimestampableInterface;
use Doctrine\Common\Collections\Collection;

interface GroupInterface extends
    ResourceInterface,
    TimestampableInterface,
    NameableInterface
{
    public function getGroupUsers(): Collection;

    public function hasGroupUser(GroupUserInterface $groupUser): bool;

    public function addGroupUser(GroupUserInterface $groupUser): void;

    public function removeGroupUser(GroupUserInterface $groupUser): void;
}
