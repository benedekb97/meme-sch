<div class="row">
    <div class="col-12 mt-1">
        <div class="d-flex justify-content-between">
            <div>
                <strong>{{ $comment->getUser()->getNickName() ?? $comment->getUser()->getName() }}</strong> <small>{{ (new Carbon\Carbon($comment->getCreatedAt()))->diffForHumans() }}</small>
            </div>
            <div>
                <div>
                    <small>
                        <a style="font-size:15px;" role="button" class="btn btn-light reply-comment p-0" data-comment-id="{{ $comment->getId() }}" data-bs-toggle="tooltip" title="Válasz">
                            <i class="bi bi-arrow-90deg-up"></i>
                        </a>
                        <a style="font-size:15px;" role="button" class="btn btn-light upvote-comment p-0" data-comment-id="{{ $comment->getId() }}" data-url="{{ route('comments.vote', ['commentId' => $comment->getId()]) }}">
                            @if ($user->hasUpvoted($comment))
                                <i class="bi bi-arrow-up-circle-fill" id="comment-{{ $comment->getId() }}-upvote-button"></i>
                            @else
                                <i class="bi bi-arrow-up-circle" id="comment-{{ $comment->getId() }}-upvote-button"></i>
                            @endif
                        </a>
                        <span id="comment-{{ $comment->getId() }}-vote-count">{{ $comment->getUpvoteCount() - $comment->getDownvoteCount() }}</span>
                        <a style="font-size:15px;" role="button" class="btn btn-light downvote-comment p-0" data-comment-id="{{ $comment->getId() }}" data-url="{{ route('comments.vote', ['commentId' => $comment->getId()]) }}">
                            @if ($user->hasDownvoted($comment))
                                <i class="bi bi-arrow-down-circle-fill" id="comment-{{ $comment->getId() }}-downvote-button"></i>
                            @else
                                <i class="bi bi-arrow-down-circle" id="comment-{{ $comment->getId() }}-downvote-button"></i>
                            @endif
                        </a>
                    </small>
                </div>
            </div>
        </div>
        <hr class="mt-0 mb-1">
        <p>{{ $comment->getComment() }}</p>
        <div id="comment-{{ $comment->getId() }}-replies">
            @foreach ($comment->getReplies() as $reply)
                @include('templates.reply', ['reply' => $reply, 'user' => $user])
            @endforeach
        </div>
        <div id="comment-{{ $comment->getId() }}-reply-form-section" class="visually-hidden">
            <div class="form-floating mb-3">
                <input data-post-id="{{ $comment->getPost()->getId() }}" data-comment-id="{{ $comment->getId() }}" onkeydown="if (event.key === 'Enter') {sendComment(event)}" type="text" class="form-control form-control-sm" id="comment-{{ $comment->getId() }}-comment" placeholder="Válasz"/>
                <label for="comment-{{ $comment->getId() }}-comment">Válasz</label>
            </div>
        </div>
    </div>
</div>
