<div class="row">
    @if ($reply->getReplyLevel() < 3)
    <div class="col-auto text-center align-content-center" style="padding-right:0;">
        <i class="bi bi-arrow-90deg-up"></i>
    </div>
    @endif
    <div class="col">
        <div class="d-flex justify-content-between">
            <div>
                <strong>{{ $reply->getUser()->getNickName() ?? $reply->getUser()->getName() }}</strong> <small>{{ (new Carbon\Carbon($reply->getCreatedAt()))->diffForHumans() }}</small>
            </div>
            <div>
                <small>
                    <a style="font-size:15px;" role="button" class="btn btn-light reply-comment p-0" data-comment-id="{{ $reply->getId() }}" data-bs-toggle="tooltip" title="Válasz">
                        <i class="bi bi-arrow-90deg-up"></i>
                    </a>
                    <a style="font-size:15px;" role="button" class="btn btn-light upvote-comment p-0" data-comment-id="{{ $reply->getId() }}" data-url="{{ route('comments.vote', ['commentId' => $reply->getId()]) }}">
                        @if ($user->hasUpvoted($reply))
                            <i class="bi bi-arrow-up-circle-fill" id="comment-{{ $reply->getId() }}-upvote-button"></i>
                        @else
                            <i class="bi bi-arrow-up-circle" id="comment-{{ $reply->getId() }}-upvote-button"></i>
                        @endif
                    </a>
                    <span id="comment-{{ $reply->getId() }}-vote-count">{{ $reply->getUpvoteCount() - $reply->getDownvoteCount() }}</span>
                    <a style="font-size:15px;" role="button" class="btn btn-light downvote-comment p-0" data-comment-id="{{ $reply->getId() }}" data-url="{{ route('comments.vote', ['commentId' => $reply->getId()]) }}">
                        @if ($user->hasDownvoted($reply))
                            <i class="bi bi-arrow-down-circle-fill" id="comment-{{ $reply->getId() }}-downvote-button"></i>
                        @else
                            <i class="bi bi-arrow-down-circle" id="comment-{{ $reply->getId() }}-downvote-button"></i>
                        @endif
                    </a>
                </small>
            </div>
        </div>
        <hr class="mt-0 mb-1">
        <p>{{ $reply->getComment() }}</p>
        <div id="comment-{{ $reply->getId() }}-replies">
            @foreach ($reply->getReplies() as $secondaryReply)
                @include('templates.reply', ['reply' => $secondaryReply, 'user' => $user])
            @endforeach
        </div>
        <div id="comment-{{ $reply->getId() }}-reply-form-section" class="visually-hidden">
            <div class="form-floating mb-3">
                <input data-post-id="{{ $reply->getPost()->getId() }}" data-comment-id="{{ $reply->getId() }}" onkeydown="if (event.key === 'Enter') {sendComment(event)}" type="text" class="form-control form-control-sm" id="comment-{{ $reply->getId() }}-comment" placeholder="Válasz"/>
                <label for="comment-{{ $reply->getId() }}-comment">Válasz</label>
            </div>
        </div>
    </div>
</div>
