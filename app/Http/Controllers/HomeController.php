<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Mail\PendingMail;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HomeController extends Controller
{
    private PostRepositoryInterface $postRepository;

    private PendingMail $mailer;

    public function __construct(
        EntityManager $entityManager,
        AuthManager $authManager,
        PostRepositoryInterface $postRepository,
        PendingMail $mailer
    ) {
        $this->postRepository = $postRepository;
        $this->mailer = $mailer;

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

    public function terms()
    {
        return view('pages.terms');
    }

    public function acceptTerms(Request $request): RedirectResponse
    {
        $user = $this->getUser();

        if (!$request->has('accept') && $request->has('accept-tldr')) {
            $user->setAcceptedTerms(true);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new RedirectResponse(route('index'));
        }

        if ($request->has('accept') && !$request->has('accept-tldr')) {
            $user->setReadTerms(true);
            $user->setAcceptedTerms(true);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new RedirectResponse(route('index'));
        }

        return new RedirectResponse(route('terms'));
    }
}
