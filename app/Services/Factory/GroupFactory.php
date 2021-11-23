<?php

declare(strict_types=1);

namespace App\Services\Factory;

use App\Entities\Group;
use App\Entities\GroupInterface;

class GroupFactory implements GroupFactoryInterface
{
    public function createWithId(int $id): GroupInterface
    {
        $group = new Group();

        $group->setId($id);

        return $group;
    }
}
