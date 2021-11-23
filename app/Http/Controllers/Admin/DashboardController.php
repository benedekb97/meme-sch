<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Services\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;
use Illuminate\View\Factory;

class DashboardController extends AdminController
{
    private PostRepositoryInterface $postRepository;

    public function __construct(
        EntityManager $entityManager,
        AuthManager $authManager,
        PostRepositoryInterface $postRepository,
        Factory $viewFactory
    )
    {
        $this->postRepository = $postRepository;

        parent::__construct($entityManager, $authManager, $postRepository, $viewFactory);
    }

    public function index()
    {
        return view('pages.admin.index');
    }

    public function posts()
    {
        return view('pages.admin.posts');
    }

    public function approvals()
    {
        $posts = $this->postRepository->findAllUnapproved();

        return view(
            'pages.admin.approvals',
            [
                'posts' => $posts,
            ]
        );
    }

    public function refusedPosts()
    {
        $posts = $this->postRepository->findAllRefused();

        return view(
            'pages.admin.refused-posts',
            [
                'posts' => $posts,
            ]
        );
    }
}
