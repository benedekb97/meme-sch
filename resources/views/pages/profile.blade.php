@extends('layouts.main')

@section('title', 'Profilom')

@section('content')
    <div class="d-flex row mt-3 justify-content-between">
        <div class="col-2">
            <div class="list-group">
                <a href="{{ route('profile') }}" class="list-group-item list-group-item-action active">Posztok</a>
                <a href="{{ route('profile.settings') }}" class="list-group-item list-group-item-action">Beállítások</a>
            </div>
        </div>
        <div class="col">
            @foreach ($user->getPosts() as $post)
                <div class="col-6 mx-auto">
                    <div class="card mb-3 {{ $post->getPostStyle() }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title mb-0 ">{{ $post->getName() }}</h5>
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
                        <img src="{{ route('image', ['postId' => $post->getId()]) }}" alt="{{ $post->getName() }}">
                        @if ($post->hasActiveRefusal())
                            <div class="card-footer">

                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
