@extends('layouts.main')

@section('title', 'Meme.SCH')

@section('content')
    <input type="hidden" id="scroll-auto" value="true"/>
    <input type="hidden" id="offset-url" value="{{ trim(route('posts.create'), DIRECTORY_SEPARATOR) }}">
    @isset($group)
        <input type="hidden" id="group-id" value="{{ $group->getId() }}">
    @endisset

    <div id="post-container">
        @if (empty($posts))
            <div class="text-center mt-5">
                <p class="text-muted" style="font-style:italic;">Nincs még itt semmi...</p>
            </div>
        @endif
        @foreach ($posts as $post)
            @include('templates.post', ['post' => $post, 'user' => $user])
        @endforeach
    </div>
@endsection
