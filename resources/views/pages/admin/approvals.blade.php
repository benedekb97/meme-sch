@extends('layouts.admin')

@section('title', 'Approvals')

@section('page-title', 'Elfogadásra váró posztok')

@section('content')
    <div class="row">
        @foreach ($posts as $post)
            <div class="col-md-4 mb-3" id="post-{{ $post->getId() }}">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">{{ $post->getName() }}</span><br>
                        <span class="card-subtitle text-muted">{{ $post->getUser()->getName() }}</span>
                    </div>
                    <img src="{{ route('image', ['postId' => $post->getId()]) }}" class="card-img-bottom" alt="{{ $post->getName() }}">
                    <div class="card-footer">
                        <button class="btn btn-sm btn-success approve-post" type="button" data-post-id="{{ $post->getId() }}" data-url="{{ route('admin.posts.approve', ['postId' => $post->getId()]) }}" data-bs-toggle="tooltip" title="Approve">
                            <i class="bi bi-check-lg" style="bottom:0;"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-post" type="button" data-post-id="{{ $post->getId() }}" data-url="{{ route('admin.posts.delete', ['postId' => $post->getId()]) }}" data-bs-toggle="tooltip" title="Delete">
                            <i class="bi bi-trash" style="bottom:0;"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
