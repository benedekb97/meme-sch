@extends('layouts.main')

@section('title', $post->getName())

@section('content')
    <div class="row justify-content-center mt-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-0">
                            {{ $post->getName() }}
                        </h5>
                        <div>
                            <span class="badge bg-secondary">
                                @if ($post->hasGroup())
                                    {{ $post->getGroup()->getName() }}
                                @else
                                    Schönherz
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <img alt="{{ $post->getName() }}" class="rounded mx-auto d-block img-fluid mb-0 mt-0 card-img-bottom" src="{{ route('posts.image', ['postId' => $post->getId()]) }}"/>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="text-muted @if($post->isAnonymous()) {{ 'fst-italic' }} @endif">
                            @if (!$post->isAnonymous() && $post->getUser()->getProfilePicture() !== null)
                                <img src="{{ route('image', ['imageId' => $post->getUser()->getProfilePicture()->getId()]) }}" style="max-height:32px; margin-right:3px;" class="rounded">
                            @endif
                            {{ !$post->isAnonymous() ? $post->getUser()->getNickName() ?? $post->getUser()->getName() : 'Anonymous' }}
                        </div>
                        <div class="">
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

            <h5 class="mt-3">Hozzászólások:</h5>

            <div class="mt-3" id="post-{{ $post->getId() }}-reply-form-section">
                <div class="form-floating mb-3">
                    <input data-post-id="{{ $post->getId() }}" onkeydown="if (event.key === 'Enter') {sendComment(event)}" type="text" class="form-control form-control-sm" id="post-{{ $post->getId() }}-comment" placeholder="Új hozzászólás..."/>
                    <label for="post-{{ $post->getId() }}-comment">Új hozzászólás...</label>
                </div>
            </div>

            <div id="post-{{ $post->getId() }}-comments">
                @foreach ($post->getComments() as $comment)
                    @include('templates.comment', ['comment' => $comment, 'user' => $user])
                @endforeach
            </div>
        </div>
    </div>
@endsection
