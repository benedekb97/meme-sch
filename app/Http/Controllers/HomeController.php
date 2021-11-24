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
        $posts = $this->postRepository->findAllWithOffset($this->getUser()->getGroups());

        return view(
            'pages.index',
            [
                'posts' => $posts,
                'user' => $this->getUser(),
            ]
        );
    }

    public function profile()
    {
        return view(
            'pages.profile',
            [
                'user' => $this->getUser(),
            ]
        );
    }
}
