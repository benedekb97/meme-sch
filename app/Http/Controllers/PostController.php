<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entities\GroupInterface;
use App\Entities\PostInterface;
use App\Entities\VoteInterface;
use App\Services\Factory\PostFactoryInterface;
use App\Services\Provider\VoteProvider;
use App\Services\Provider\VoteProviderInterface;
use App\Services\Repository\GroupRepositoryInterface;
use App\Services\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public const MIME_TYPE_JPEG = 'image/jpeg';
    public const MIME_TYPE_PNG = 'image/png';
    public const MIME_TYPE_GIF = 'image/gif';

    public const ALLOWED_MIME_TYPES = [
        self::MIME_TYPE_JPEG,
        self::MIME_TYPE_PNG,
        self::MIME_TYPE_GIF,
    ];

    public const CONVERTABLE_MIME_TYPES = [
        self::MIME_TYPE_JPEG,
        self::MIME_TYPE_PNG,
    ];

    private PostFactoryInterface $postFactory;

    private PostRepositoryInterface $postRepository;

    private VoteProviderInterface $voteProvider;

    private GroupRepositoryInterface $groupRepository;

    public function __construct(
        EntityManager $entityManager,
        AuthManager $authManager,
        PostFactoryInterface $postFactory,
        PostRepositoryInterface $postRepository,
        VoteProvider $voteProvider,
        GroupRepositoryInterface $groupRepository
    ) {
        $this->postFactory = $postFactory;
        $this->postRepository = $postRepository;
        $this->voteProvider = $voteProvider;
        $this->groupRepository = $groupRepository;

        parent::__construct($entityManager, $authManager);
    }

    public function index(Request $request, ?int $offset = 0): JsonResponse
    {
        if (($groupId = $request->get('groupId')) !== null) {
            $group = $this->groupRepository->find($groupId);

            if ($group === null) {
                return new JsonResponse(
                    [
                        'error' => 'Group could not be found!',
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }

            if (!$this->getUser()->hasGroup($group)) {
                return new JsonResponse(
                    [
                        'error' => 'User cannot view posts in group!',
                    ],
                    Response::HTTP_FORBIDDEN
                );
            }

            $posts = $this->postRepository->findAllForGroup($group, $offset);
        } else {
            $posts = $this->postRepository->findAllWithOffset($this->getUser()->getGroups(), $offset);
        }

        $postsHtml = [];

        /** @var PostInterface $post */
        foreach ($posts as $post) {
            $postsHtml[] = view(
                'templates.post',
                [
                    'post' => $post,
                    'user' => $this->getUser(),
                ]
            )->render();
        }

        return new JsonResponse($postsHtml, Response::HTTP_OK);
    }

    public function show(int $postId)
    {
        /** @var PostInterface|null $post */
        $post = $this->postRepository->find($postId);

        if ($post === null) {
            abort(404);
        }

        return view(
            'pages.posts.show',
            [
                'post' => $post,
                'user' => $this->getUser(),
            ]
        );
    }

    public function edit(int $postId)
    {

    }

    public function delete(int $postId)
    {

    }

    public function create(Request $request): JsonResponse
    {
        $title = $request->get('title');

        /** @var UploadedFile $file */
        $file = $request->file('file');

        $groupId = $request->get('groupId');

        $user = $this->getUser();

        if ($groupId !== null) {
            /** @var GroupInterface $group */
            $group = $this->groupRepository->find($groupId);
        }

        if (isset($group) && (!$user->hasGroup($group) || !$user->canPostInGroup($group))) {
            $groupError = false;
        }

        $anonymous = ($request->get('anonymous') === 'true');

        if ($file === null) {
            $fileError = false;
        } elseif (!in_array($mimeType = $file->getMimeType(), self::ALLOWED_MIME_TYPES, true)) {
            $fileError = 'mimeType';
        }

        if ($title === null) {
            $titleError = false;
        } elseif (strlen($title) > 255) {
            $titleError = 'length';
        }

        if (isset($fileError) || isset($titleError) || isset($groupError)) {
            return new JsonResponse(
                [
                    'title' => $titleError ?? true,
                    'file' => $fileError ?? true,
                    'group' => $groupError ?? true,
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $fileName = Str::random() . '.' .  $file->getClientOriginalExtension();

        $file->storeAs('images', $fileName);

        $filePath = sprintf('images/%s', $fileName);

        $post = $this->postFactory->createWithUser($this->getUser());
        $post->setName($title);
        $post->getImage()->setFilePath($filePath);
        $post->getImage()->setConvertable(in_array($mimeType ?? null, self::CONVERTABLE_MIME_TYPES, true));
        $post->setAnonymous($anonymous);

        if (isset($group)) {
            $post->setGroup($group);
        }

        if (!$anonymous) {
            $post->setApprovedAtNow();
        }

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $vote = $this->voteProvider->provideForUserAndVoteable($this->getUser(), $post);

        $vote->setType(VoteInterface::TYPE_UP);

        $this->entityManager->persist($vote);
        $this->entityManager->flush();

        $postHtml = view('templates.post', ['post' => $post, 'user' => $this->getUser()])->render();

        return new JsonResponse(
            [
                'success' => true,
                'post' => !$anonymous ? $postHtml : null,
            ],
            Response::HTTP_OK
        );
    }

    public function update(Request $request, int $postId)
    {

    }

    public function vote(Request $request, int $postId): JsonResponse
    {
        /** @var PostInterface $post */
        $post = $this->postRepository->find($postId);

        if ($post === null) {
            return new JsonResponse(
                [
                    'error' => 'The requested entity could not be found.',
                    'code'  => Response::HTTP_NOT_FOUND,
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if (!$request->has('type') || !in_array($type = $request->get('type'),VoteInterface::TYPE_MAP, true)) {
            return new JsonResponse(
                [
                    'error' => sprintf('Invalid vote type \'%s\'', $type ?? 'null'),
                    'code' => Response::HTTP_BAD_REQUEST
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $vote = $this->voteProvider->provideForUserAndVoteable($this->getUser(), $post);

        if ($vote->getType() === $type) {
            $this->entityManager->remove($vote);
            $this->entityManager->flush();

            /** @var PostInterface $post */
            $post = $this->postRepository->find($post->getId());

            return new JsonResponse(
                [
                    'vote' => null,
                    'score' => $post->getUpvoteCount() - $post->getDownvoteCount(),
                ],
                Response::HTTP_OK
            );
        }

        $vote->setType($request->get('type'));

        $this->entityManager->persist($vote);
        $this->entityManager->flush();

        /** @var PostInterface $post */
        $post = $this->postRepository->find($post->getId());

        return new JsonResponse(
            [
                'vote' => [
                    'type' => $vote->getType()
                ],
                'score' => $post->getUpvoteCount() - $post->getDownvoteCount(),
            ],
            Response::HTTP_OK
        );
    }

    public function search(Request $request)
    {
        $term = $request->get('search') ?? '';

        $groups = $this->getUser()->getGroups();

        $posts = $this->postRepository->searchByGroups($groups, $term);

        return view(
            'pages.index',
            [
                'user' => $this->getUser(),
                'posts' => $posts,
                'search' => $term,
            ]
        );
    }
}
