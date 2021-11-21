<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Entities\PostInterface;
use App\Http\Controllers\Controller;
use App\Services\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
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

    public function approve(int $postId): JsonResponse
    {
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

    public function delete(int $postId): JsonResponse
    {
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

        $post->delete();

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
