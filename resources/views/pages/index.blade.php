@extends('layouts.main')

@section('title', 'Meme.SCH')

@section('content')
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
