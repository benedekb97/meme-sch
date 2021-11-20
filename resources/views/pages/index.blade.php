@extends('layouts.main')

@section('title', 'meme.sch.bme.hu')

@section('content')
    @foreach ($posts as $post)
        <div class="row mt-3 justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->getName() }}</h5>
                        <img alt="{{ $post->getName() }}" class="rounded mx-auto d-block img-fluid mb-3" src="{{ route('image', ['postId' => $post->getId()]) }}"/>
                        <div class="d-flex justify-content-between">
                            <div class="text-muted">{{ $post->getUser()->getNickName() ?? $post->getUser()->getName() }}</div>
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
            </div>
        </div>
    @endforeach
@endsection
