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

        if (
            $post === null ||
            $post->getImage() === null ||
            (
                $post->getGroup() !== null &&
                !$this->getUser()->getGroups()->contains($post->getGroup()) &&
                !$this->getUser()->isAdministrator()
            )
        ) {
            return new Response(null, SymfonyResponse::HTTP_NOT_FOUND);
        }

        return ResponseFacade::make(
            $this->filesystem->get($post->getImage()->getFilePath())
        )->header(
            'Content-Type', $this->filesystem->mimeType($post->getImage()->getFilePath())
        );
    }

    public function getImageWithSource(int $imageId, int $width): Response
    {
        /** @var ImageInterface $image */
        $image = $this->imageRepository->find($imageId);

        if (
            $image === null ||
            ($image->getPost() !== null && !$this->getUser()->isAdministrator()) ||
            !$image->hasSourceSet() ||
            !array_key_exists($width, $image->getSourceSet())
        ) {
            return new Response(null, SymfonyResponse::HTTP_NOT_FOUND);
        }

        return ResponseFacade::make(
            $this->filesystem->get($image->getSourceSet()[$width])
        )->header(
            'Content-Type', $this->filesystem->mimeType($image->getSourceSet()[$width])
        );
    }

    public function getPostWithSource(int $postId, int $width): Response
    {
        /** @var PostInterface $post */
        $post = $this->postRepository->find($postId);

        if (
            $post === null ||
            $post->getImage() === null ||
            !$post->getImage()->hasSourceSet() ||
            (
                $post->hasGroup() &&
                !$this->getUser()->hasGroup($post->getGroup()) &&
                !$this->getUser()->isAdministrator()
            ) ||
            !array_key_exists($width, $post->getImage()->getSourceSet())
        ) {
            return new Response(null, SymfonyResponse::HTTP_NOT_FOUND);
        }

        return ResponseFacade::make(
            $this->filesystem->get($post->getImage()->getSourceSet()[$width])
        )->header(
            'Content-Type', $this->filesystem->mimeType($post->getImage()->getSourceSet()[$width])
        );
    }
}
