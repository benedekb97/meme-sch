<?php

declare(strict_types=1);

namespace App\Http\Controllers;


use App\Entities\CommentInterface;
use App\Entities\VoteInterface;
use App\Services\Factory\CommentFactoryInterface;
use App\Services\Provider\VoteProvider;
use App\Services\Provider\VoteProviderInterface;
use App\Services\Repository\CommentRepositoryInterface;
use App\Services\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    private CommentRepositoryInterface $commentRepository;

    private VoteProviderInterface $voteProvider;

    private PostRepositoryInterface $postRepository;

    private CommentFactoryInterface $commentFactory;

    public function __construct(
        EntityManager $entityManager,
        AuthManager $authManager,
        CommentRepositoryInterface $commentRepository,
        VoteProvider $voteProvider,
        PostRepositoryInterface $postRepository,
        CommentFactoryInterface $commentFactory
    )
    {
        $this->commentRepository = $commentRepository;
        $this->voteProvider = $voteProvider;
        $this->postRepository = $postRepository;
        $this->commentFactory = $commentFactory;

        parent::__construct($entityManager, $authManager);
    }

    public function create(Request $request): JsonResponse
    {
        $replyTo = $request->has('replyTo') && $request->get('replyTo') !== null
            ? $this->commentRepository->find($request->get('replyTo'))
            : null;

        $post = $request->has('post')
            ? $this->postRepository->find($request->get('post'))
            : null;

        $text = $request->get('comment') ?? null;

        if (!isset($post) || !isset($text)) {
            return new JsonResponse(
                [
                    'success' => false,
                    'error' => 'Post ID or comment text missing.'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $comment = $this->commentFactory->createWithUserPostAndComment($user = $this->getUser(), $post, $replyTo);

        $comment->setComment($text);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
        $this->entityManager->refresh($comment);

        return new JsonResponse(
            [
                'success' => true,
                'comment' => [
                    'html' => $comment->hasReplyTo()
                        ? view('templates.reply', ['reply' => $comment, 'user' => $user])->render()
                        : view('templates.comment', ['comment' => $comment, 'user' => $user])->render(),
                ],
            ],
            Response::HTTP_OK
        );
    }

    public function vote(int $commentId, Request $request): JsonResponse
    {
        /** @var CommentInterface $comment */
        $comment = $this->commentRepository->find($commentId);

        if ($comment === null) {
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

        $vote = $this->voteProvider->provideForUserAndVoteable($this->getUser(), $comment);

        if ($vote->getType() === $type) {
            $this->entityManager->remove($vote);
            $this->entityManager->flush();

            /** @var CommentInterface $comment */
            $comment = $this->commentRepository->find($comment->getId());

            return new JsonResponse(
                [
                    'vote' => null,
                    'score' => $comment->getUpvoteCount() - $comment->getDownvoteCount(),
                ],
                Response::HTTP_OK
            );
        }

        $vote->setType($request->get('type'));

        $this->entityManager->persist($vote);
        $this->entityManager->flush();

        /** @var CommentInterface $comment */
        $comment = $this->commentRepository->find($comment->getId());

        return new JsonResponse(
            [
                'vote' => [
                    'type' => $vote->getType()
                ],
                'score' => $comment->getUpvoteCount() - $comment->getDownvoteCount(),
            ],
            Response::HTTP_OK
        );
    }
}
