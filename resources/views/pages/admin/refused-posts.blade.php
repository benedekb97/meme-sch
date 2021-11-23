@extends('layouts.admin')

@section('title', 'Elutasított posztok')

@section('page-title', 'Elutasított posztok')

@section('content')
    <div class="row">
        @foreach ($posts as $post)
            <div class="col-sm-6 col-lg-4 mb-3 col-xl-3" id="post-{{ $post->getId() }}">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">{{ $post->getName() }}</span><br>
                        <span class="card-subtitle text-muted">{{ $post->getUser()->getName() }}</span>
                    </div>
                    <img src="{{ route('image', ['postId' => $post->getId()]) }}" class="card-img-bottom" alt="{{ $post->getName() }}">
                    <div class="table-responsive table-responsive-sm">
                        <table class="table table-striped table-sm mb-0">
                            <tr>
                                <th colspan="3" class="text-center">Történet</th>
                            </tr>
                            @foreach ($post->getRefusals() as $refusal)
                                <tr>
                                    <td>
                                        {{ $refusal->getUser()->getNickName() ?? $refusal->getUser()->getName() }}
                                    </td>
                                    <td>
                                        {{ (new Carbon\Carbon($refusal->getCreatedAt()))->diffForHumans() }}
                                    </td>
                                    <td>
                                        {!! $refusal->getReason() ?? '<i>Nincs indoklás...</i>' !!}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-success restore-post" type="button" data-post-id="{{ $post->getId() }}" data-url="{{ route('admin.posts.restore', ['postId' => $post->getId()]) }}" data-bs-toggle="tooltip" title="Visszaállítás">
                            <i class="bi bi-check-lg" style="bottom:0;"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
