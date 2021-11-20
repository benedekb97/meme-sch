<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;

class HomeController extends Controller
{
    private PostRepositoryInterface $postRepository;

    public function __construct(
        EntityManager $entityManager,
        AuthManager $authManager,
        PostRepositoryInterface $postRepository
    ) {
        $this->postRepository = $postRepository;

        parent::__construct($entityManager, $authManager);
    }

    public function index()
    {
        $posts = $this->postRepository->findAllWithOffset();

        return view(
            'pages.index',
            [
                'posts' => $posts,
                'user' => $this->getUser(),
            ]
        );
    }
}
