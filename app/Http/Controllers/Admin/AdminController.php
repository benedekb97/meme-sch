<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Entities\GroupInterface;
use App\Entities\PostInterface;
use App\Entities\UserInterface;
use App\Http\Controllers\Controller;
use App\Services\Repository\PostRepositoryInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;
use Illuminate\View\Factory;

class AdminController extends Controller
{
    protected ?Collection $adminGroups = null;

    protected Factory $viewFactory;

    protected PostRepositoryInterface $postRepository;

    public function __construct(
        EntityManager           $entityManager,
        AuthManager             $authManager,
        PostRepositoryInterface $postRepository,
        Factory                 $viewFactory
    )
    {
        parent::__construct($entityManager, $authManager);

        $this->viewFactory = $viewFactory;
        $this->postRepository = $postRepository;
    }

    protected function load(): void
    {
        /** @var UserInterface $user */
        $user = $this->auth->user();

        if (!$user->isAdministrator()) {
            $this->adminGroups = $user->getGroupsWithLeadership();
        }

        if (!isset($this->adminGroups)) {
            $this->viewFactory->share(
                'unapprovedPostCount', $this->postRepository->countUnapproved()
            );

            $this->viewFactory->share(
                'refusedPostCount', $this->postRepository->countRefused()
            );
        } else {
            $this->viewFactory->share(
                'unapprovedPostCount', $this->postRepository->countUnapprovedForGroups($this->adminGroups)
            );

            $this->viewFactory->share(
                'refusedPostCount', $this->postRepository->countRefusedForGroups($this->adminGroups)
            );
        }
    }

    protected function hasPermission(?PostInterface $post = null, ?GroupInterface $group = null): bool
    {
        $user = $this->getUser();

        if ($user->isAdministrator()) {
            return true;
        }

        if ($group === null && $post === null) {
            return false;
        }

        $group = $group ?? $post->getGroup();

        if ($group === null) {
            return false;
        }

        if (!isset($this->adminGroups)) {
            return false;
        }

        return $this->adminGroups->contains($group);
    }
}
