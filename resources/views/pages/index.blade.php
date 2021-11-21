@extends('layouts.main')

@section('title', 'meme.sch.bme.hu')

@section('content')
    <div id="post-container">
        @foreach ($posts as $post)
            @include('templates.post', ['post' => $post, 'user' => $user])
        @endforeach
    </div>
@endsection
