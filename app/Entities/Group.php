<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\NameableTrait;
use App\Entities\Traits\ResourceTrait;
use App\Entities\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Group implements GroupInterface
{
    use ResourceTrait;
    use TimestampableTrait;
    use NameableTrait;

    private Collection $groupUsers;

    private Collection $posts;

    public function __construct()
    {
        $this->groupUsers = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getGroupUsers(): Collection
    {
        return $this->groupUsers;
    }

    public function hasGroupUser(GroupUserInterface $groupUser): bool
    {
        return $this->groupUsers->contains($groupUser);
    }

    public function addGroupUser(GroupUserInterface $groupUser): void
    {
        if (!$this->hasGroupUser($groupUser)) {
            $this->groupUsers->add($groupUser);
            $groupUser->setGroup($this);
        }
    }

    public function removeGroupUser(GroupUserInterface $groupUser): void
    {
        if ($this->hasGroupUser($groupUser)) {
            $this->groupUsers->removeElement($groupUser);
            $groupUser->setGroup(null);
        }
    }

    public function hasUser(UserInterface $user): bool
    {
        return !$this->groupUsers->filter(
            static function (GroupUserInterface $groupUser) use ($user): bool
            {
                return $groupUser->getUser() === $user;
            }
        )->isEmpty();
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function hasPost(PostInterface $post): bool
    {
        return $this->posts->contains($post);
    }

    public function addPost(PostInterface $post): void
    {
        if (!$this->hasPost($post)) {
            $this->posts->add($post);
            $post->setGroup($this);
        }
    }

    public function removePost(PostInterface $post): void
    {
        if ($this->hasPost($post)) {
            $this->posts->removeElement($post);
            $post->setGroup(null);
        }
    }
}
