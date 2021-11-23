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
                                @if (!$post->isApproved() && !$post->isDeleted() && $post->isAnonymous())
                                    <div>
                                        <i><small>Elfogadásra vár</small></i>
                                    </div>
                                @elseif ($post->isDeleted())
                                    <div class="text-white">
                                        <i><small>Elutasítva</small></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <img src="{{ route('image', ['postId' => $post->getId()]) }}" alt="{{ $post->getName() }}">
                        @if ($post->isDeleted())
                            <div class="card-footer">

                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
