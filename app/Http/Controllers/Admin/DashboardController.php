<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;

class DashboardController extends Controller
{
    private PostRepositoryInterface $postRepository;

    public function __construct(
        EntityManager $entityManager,
        AuthManager $authManager,
        PostRepositoryInterface $postRepository
    )
    {
        $this->postRepository = $postRepository;

        parent::__construct($entityManager, $authManager);
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
}
