<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entities\ImageInterface;
use App\Entities\PostInterface;
use App\Services\Repository\ImageRepositoryInterface;
use App\Services\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ImageController extends Controller
{
    private ImageRepositoryInterface $imageRepository;

    private PostRepositoryInterface $postRepository;

    private Filesystem $filesystem;

    public function __construct(
        EntityManager            $entityManager,
        AuthManager              $authManager,
        ImageRepositoryInterface $imageRepository,
        FilesystemManager        $filesystemManager,
        PostRepositoryInterface  $postRepository
    )
    {
        $this->imageRepository = $imageRepository;
        $this->postRepository = $postRepository;
        $this->filesystem = $filesystemManager->disk(config('filesystems.default'));

        parent::__construct($entityManager, $authManager);
    }

    public function get(int $imageId): Response
    {
        /** @var ImageInterface $image */
        $image = $this->imageRepository->find($imageId);

        if ($image === null || ($image->getPost() !== null && !$this->getUser()->isAdministrator())) {
            return new Response(null, SymfonyResponse::HTTP_NOT_FOUND);
        }

        return ResponseFacade::make(
            $this->filesystem->get($image->getFilePath())
        )->header(
            'Content-Type', $this->filesystem->mimeType($image->getFilePath())
        );
    }

    public function getPost(int $postId): Response
    {
        /** @var PostInterface|null $post */
        $post = $this->postRepository->find($postId);

        if ($post === null || $post->getImage() === null) {
            return new Response(null, SymfonyResponse::HTTP_NOT_FOUND);
        }

        return ResponseFacade::make(
            $this->filesystem->get($post->getImage()->getFilePath())
        )->header(
            'Content-Type', $this->filesystem->mimeType($post->getImage()->getFilePath())
        );
    }
}
