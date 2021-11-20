<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entities\PostInterface;
use App\Services\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as ResponseFacade;

class ImageController extends Controller
{
    private PostRepositoryInterface $postRepository;

    private Filesystem $filesystem;

    public function __construct(
        EntityManager           $entityManager,
        AuthManager             $authManager,
        PostRepositoryInterface $postRepository,
        FilesystemManager       $filesystemManager
    )
    {
        $this->postRepository = $postRepository;
        $this->filesystem = $filesystemManager->disk(config('filesystems.default'));

        parent::__construct($entityManager, $authManager);
    }

    public function get(int $postId): Response
    {
        /** @var PostInterface $post */
        $post = $this->postRepository->find($postId);

        return ResponseFacade::make(
            $this->filesystem->get($post->getFilePath())
        )->header(
            'Content-Type', $this->filesystem->mimeType($post->getFilePath())
        );
    }
}
