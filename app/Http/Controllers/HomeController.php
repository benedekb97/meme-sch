<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entities\PostInterface;
use App\Entities\ReportInterface;
use App\Services\Factory\ReportFactoryInterface;
use App\Services\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    private PostRepositoryInterface $postRepository;

    private ReportFactoryInterface $reportFactory;

    public function __construct(
        EntityManager $entityManager,
        AuthManager $authManager,
        PostRepositoryInterface $postRepository,
        ReportFactoryInterface $reportFactory
    ) {
        $this->postRepository = $postRepository;
        $this->reportFactory = $reportFactory;

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
        return view(
            'pages.terms',
            [
                'user' => $this->getUser(),
            ]
        );
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

    public function report(Request $request): JsonResponse
    {
        $postId = $request->get('post');
        $reason = $request->get('reason');

        if ($postId === null) {
            return new JsonResponse(
                [
                    'error' => 'No postId provided!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        if ($reason === null || !in_array($reason, ReportInterface::REASON_MAP, true)) {
            return new JsonResponse(
                [
                    'error' => 'Invalid reason provided!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        /** @var PostInterface $post */
        $post = $this->postRepository->find($postId);

        if ($post === null) {
            return new JsonResponse(
                [
                    'error' => 'Specified post could not be found!'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if ($post->hasReportByUser($user = $this->getUser())) {
            return new JsonResponse(
                [
                    'error' => 'Post already reported by user!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $report = $this->reportFactory->createForUserAndPost($user, $post);

        $report->setReason($reason);
        $report->setStatus(ReportInterface::STATUS_AWAITING_JUDGEMENT);

        $this->entityManager->persist($report);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
