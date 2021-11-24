<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Entities\PostInterface;
use App\Services\Repository\GroupRepositoryInterface;
use App\Services\Repository\PostRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Arr;
use Illuminate\View\Factory;

class DashboardController extends AdminController
{
    private GroupRepositoryInterface $groupRepository;

    public function __construct(
        EntityManager $entityManager,
        AuthManager $authManager,
        PostRepositoryInterface $postRepository,
        Factory $viewFactory,
        GroupRepositoryInterface $groupRepository
    )
    {
        parent::__construct($entityManager, $authManager, $postRepository, $viewFactory);

        $this->groupRepository = $groupRepository;
    }

    public function index()
    {
        $this->load();

        return view('pages.admin.index');
    }

    public function posts()
    {
        $this->load();

        return view('pages.admin.posts');
    }

    public function approvals()
    {
        $this->load();

        if (isset($this->adminGroups)) {
            $posts = $this->postRepository->findAllUnapprovedForGroups($this->adminGroups);
        } else {
            $posts = $this->postRepository->findAllUnapproved();
        }

        $groups = new ArrayCollection();
        $users = new ArrayCollection();

        foreach ($posts as $post) {
            if (!$groups->contains($post->getGroup())) {
                $groups->add($post->getGroup());
            }

            if (!$users->contains($post->getUser())) {
                $users->add($post->getUser());
            }
        }

        return view(
            'pages.admin.approvals',
            [
                'posts' => $posts,
                'user' => $this->getUser(),
                'groups' => $groups,
                'users' => $users,
            ]
        );
    }

    public function refusedPosts()
    {
        $this->load();

        if (isset($this->adminGroups)) {
            $posts = $this->postRepository->findAllRefusedForGroups($this->adminGroups);
        } else {
            $posts = $this->postRepository->findAllRefused();
        }

        return view(
            'pages.admin.refused-posts',
            [
                'posts' => $posts,
            ]
        );
    }
}
