<div class="row mt-3 justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <a class="text-decoration-none text-black" href="{{ route('posts.show', ['postId' => $post->getId()]) }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-0">
                            {{ $post->getName() }}
                        </h5>
                        <div>
                            <span class="badge bg-secondary">@if ($post->hasGroup()) {{ $post->getGroup()->getName() }} @else Schönherz @endif</span>
                        </div>
                    </div>
                </div>
                <img alt="{{ $post->getName() }}" class="mx-auto d-block img-fluid mb-0 mt-0 card-img-bottom" src="{{ route('posts.image', ['postId' => $post->getId()]) }}"/>
            </a>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="text-muted @if($post->isAnonymous()) {{ 'fst-italic' }} @endif">
                        @if (!$post->isAnonymous() && $post->getUser()->getProfilePicture() !== null)
                            <img src="{{ route('image', ['imageId' => $post->getUser()->getProfilePicture()->getId()]) }}" style="max-height:32px; margin-right:3px;" class="rounded">
                        @endif
                        {{ !$post->isAnonymous() ? $post->getUser()->getNickName() ?? $post->getUser()->getName() : 'Anonymous' }}
                    </div>
                    <div>
                        <button type="button" class="btn btn-light upvote-post" data-post-id="{{ $post->getId() }}" data-url="{{ route('posts.vote', ['postId' => $post->getId()]) }}">
                            @if ($user->hasUpvoted($post))
                                <i class="bi bi-arrow-up-circle-fill" id="post-{{ $post->getId() }}-upvote-button"></i>
                            @else
                                <i class="bi bi-arrow-up-circle" id="post-{{ $post->getId() }}-upvote-button"></i>
                            @endif
                        </button>
                        <button disabled type="button" class="btn btn-outline-dark" id="post-{{ $post->getId() }}-vote-count">{{ $post->getUpvoteCount() - $post->getDownvoteCount() }}</button>
                        <button type="button" class="btn btn-light downvote-post" data-post-id="{{ $post->getId() }}" data-url="{{ route('posts.vote', ['postId' => $post->getId()]) }}">
                            @if ($user->hasDownvoted($post))
                                <i class="bi bi-arrow-down-circle-fill" id="post-{{ $post->getId() }}-downvote-button"></i>
                            @else
                                <i class="bi bi-arrow-down-circle" id="post-{{ $post->getId() }}-downvote-button"></i>
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
