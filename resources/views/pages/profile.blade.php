@extends('layouts.main')

@section('title', 'Profilom')

@section('content')
    <div class="d-flex row mt-3 justify-content-between">
        <div class="col-lg-2 mb-3">
            <div class="list-group">
                <a href="{{ route('profile') }}" class="list-group-item list-group-item-action active">Posztok</a>
                <a href="{{ route('profile.settings') }}" class="list-group-item list-group-item-action">Beállítások</a>
            </div>
        </div>
        <div class="col-lg">
            <div class="col-lg-6 mx-auto">

                <div class="card border-white">
                    <div class="card-body">
                        <h5 class="mb-0 card-title">{{ $user->getName() }}</h5>
                    </div>
                </div>
            </div>
            @foreach ($user->getSortedPosts() as $post)
                <div class="col-lg-8 mx-auto">
                    <div class="card mb-3 {{ $post->getPostStyle() }}">
                        @if ($post->isApproved() && !$post->hasActiveRefusal() || !$post->isAnonymous())
                            <a href="{{ route('posts.show', ['postId' => $post->getId()]) }}" class="text-decoration-none text-black">
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title mb-0 ">{{ $post->getName() }} <small class="badge bg-secondary position-relative mx-1" style="bottom:2px;"> @if($post->hasGroup()) {{ $post->getGroup()->getName() }} @else Schönherz @endif </small></h5>
                                </div>
                                @if (!$post->isApproved() && !$post->hasActiveRefusal() && $post->isAnonymous())
                                    <div>
                                        <i><small>Elfogadásra vár</small></i>
                                    </div>
                                @elseif ($post->hasActiveRefusal())
                                    <div class="text-white" data-bs-toggle="popover" data-bs-placement="right" data-bs-trigger="hover" data-bs-html="true" data-bs-content="
                                        @if ($post->getRefusal()->hasReason())
                                            {{ $post->getRefusal()->getReason() }}<br><i>- {{ $post->getRefusal()->getUser()->getNickName() ?? $post->getRefusal()->getUser()->getName() }},
                                            {{ (new Carbon\Carbon($post->getRefusal()->getCreatedAt()))->diffForHumans() }}</i>
                                        @else
                                            <i>Nincs indoklás...</i>
                                        @endif
                                        " title="Elutasítás indoklása">
                                        <i><small>Elutasítva</small></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <img src="{{ route('posts.image', ['postId' => $post->getId()]) }}" alt="{{ $post->getName() }}" class="mx-auto d-block img-fluid mb-0 mt-0 card-img-bottom">

                        @if ($post->isApproved() || !$post->isAnonymous())
                        </a>
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="text-muted @if($post->isAnonymous()) {{ 'fst-italic' }} @endif">{{ !$post->isAnonymous() ? $post->getUser()->getNickName() ?? $post->getUser()->getName() : 'Anonymous' }}</div>
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
                        @endif
                        @if ($post->hasActiveRefusal())
                            <div class="card-footer">
                                Szerkesztés lehetősége jön majd ide (később, kek)
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
