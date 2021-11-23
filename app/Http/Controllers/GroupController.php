<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Repository\GroupRepositoryInterface;
use App\Services\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;

class GroupController extends Controller
{
    private PostRepositoryInterface $postRepository;

    private GroupRepositoryInterface $groupRepository;

    public function __construct(
        EntityManager $entityManager,
        AuthManager $authManager,
        PostRepositoryInterface $postRepository,
        GroupRepositoryInterface $groupRepository
    )
    {
        parent::__construct($entityManager, $authManager);

        $this->postRepository = $postRepository;
        $this->groupRepository = $groupRepository;
    }

    public function posts(int $groupId)
    {
        $group = $this->groupRepository->find($groupId);

        if ($group === null) {
            abort(404);
        }

        if (!$this->getUser()->getGroups()->contains($group)) {
            abort(404);
        }

        $posts = $this->postRepository->findAllForGroup($group);

        return view(
            'pages.index',
            [
                'user' => $this->getUser(),
                'posts' => $posts,
                'group' => $group,
            ]
        );
    }
}
