<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Entities\PostInterface;
use App\Services\Factory\RefusalFactoryInterface;
use App\Services\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;
use Illuminate\View\Factory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends AdminController
{
    private RefusalFactoryInterface $refusalFactory;

    public function __construct(
        EntityManager           $entityManager,
        AuthManager             $authManager,
        PostRepositoryInterface $postRepository,
        Factory                 $viewFactory,
        RefusalFactoryInterface $refusalFactory
    )
    {
        $this->refusalFactory = $refusalFactory;

        parent::__construct($entityManager, $authManager, $postRepository, $viewFactory);
    }

    public function approve(int $postId): JsonResponse
    {
        $this->load();

        /** @var PostInterface|null $post */
        $post = $this->postRepository->find($postId);

        if ($post === null) {
            return new JsonResponse(
                [
                    'error' => sprintf('Could not find entity with id \'%d\'', $postId),
                    'code' => Response::HTTP_NOT_FOUND,
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if (!$this->hasPermission($post)) {
            return new JsonResponse(
                [
                    'error' => 'You do not have permission to modify that entity!',
                    'code' => Response::HTTP_UNAUTHORIZED,
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $post->setApprovedBy($this->getUser());
        $post->setApprovedAtNow();

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return new JsonResponse(
            [
                'success' => true,
            ],
            Response::HTTP_OK
        );
    }

    public function refuse(Request $request, int $postId): JsonResponse
    {
        $this->load();

        /** @var PostInterface|null $post */
        $post = $this->postRepository->find($postId);

        if ($post === null) {
            return new JsonResponse(
                [
                    'error' => sprintf('Could not find entity with id \'%d\'', $postId),
                    'code' => Response::HTTP_NOT_FOUND,
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if (!$this->hasPermission($post)) {
            return new JsonResponse(
                [
                    'error' => 'You do not have permission to modify that entity!',
                    'code' => Response::HTTP_UNAUTHORIZED,
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $post->setApprovedBy(null);

        $refusal = $this->refusalFactory->createForPostByUser($post, $this->getUser());

        $refusal->setReason($request->get('reason'));

        $this->entityManager->persist($post);
        $this->entityManager->persist($refusal);
        $this->entityManager->flush();

        return new JsonResponse(
            [
                'success' => true,
            ],
            Response::HTTP_OK
        );
    }

    public function restore(int $postId): JsonResponse
    {
        $this->load();

        /** @var PostInterface|null $post */
        $post = $this->postRepository->find($postId);

        if ($post === null) {
            return new JsonResponse(
                [
                    'error' => sprintf('Could not find entity with id \'%d\'', $postId),
                    'code' => Response::HTTP_NOT_FOUND,
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if (!$this->hasPermission($post)) {
            return new JsonResponse(
                [
                    'error' => 'You do not have permission to modify that entity!',
                    'code' => Response::HTTP_UNAUTHORIZED,
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $post->setApprovedBy(null);
        $post->setRefusal(null);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return new JsonResponse(
            [
                'success' => true,
            ],
            Response::HTTP_OK
        );
    }
}
