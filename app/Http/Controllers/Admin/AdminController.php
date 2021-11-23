<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;
use Illuminate\View\Factory;

class AdminController extends Controller
{
    public function __construct(
        EntityManager $entityManager,
        AuthManager $authManager,
        PostRepositoryInterface $postRepository,
        Factory $viewFactory
    )
    {
        parent::__construct($entityManager, $authManager);

        $viewFactory->share(
            'unapprovedPostCount', $postRepository->countUnapproved()
        );

        $viewFactory->share(
            'refusedPostCount', $postRepository->countRefused()
        );
    }
}
