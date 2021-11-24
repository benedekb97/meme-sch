@extends('layouts.admin')

@section('title', 'Approvals')

@section('page-title', 'Elfogadásra váró posztok')

@section('content')
    <div class="row">
        @foreach ($posts as $post)
            <div class="col-sm-6 col-lg-4 mb-3 col-xl-3" id="post-{{ $post->getId() }}">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="card-title">{{ $post->getName() }}</span><br>
                                <span class="card-subtitle text-muted">{{ $post->getUser()->getName() }}</span>
                            </div>
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
                    <img src="{{ route('posts.image', ['postId' => $post->getId()]) }}" class="card-img-bottom" alt="{{ $post->getName() }}">
                    <div class="card-footer">
                        <button class="btn btn-sm btn-success approve-post" type="button" data-post-id="{{ $post->getId() }}" data-url="{{ route('admin.posts.approve', ['postId' => $post->getId()]) }}" data-bs-toggle="tooltip" title="Elfogadás">
                            <i class="bi bi-check-lg" style="bottom:0;"></i>
                        </button>
                        <div class="d-inline-block" data-bs-toggle="tooltip" title="Elutasítás">
                            <button class="btn btn-sm btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#refuse-post-modal-{{ $post->getId() }}">
                                <i class="bi bi-trash" style="bottom:0;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('modals')
    @foreach ($posts as $post)
        <div class="modal fade" id="refuse-post-modal-{{ $post->getId() }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        Poszt elutasítása
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    @if ($post->getRefusals()->count() !== 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <tr>
                                    <th colspan="3">Korábbi indoklások</th>
                                </tr>
                                <tr>
                                    <th>Név</th>
                                    <th>Mikor</th>
                                    <th>Indoklás</th>
                                </tr>
                                @foreach ($post->getRefusals() as $refusal)
                                    <tr>
                                        <td>{{ $refusal->getUser()->getNickName() ?? $refusal->getUser()->getName() }}</td>
                                        <td>{{ (new Carbon\Carbon($refusal->getCreatedAt()))->diffForHumans() }}</td>
                                        <td>{!! $refusal->getReason() ?? '<i>Nincs indoklás...</i>' !!}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @endif
                    <div class="modal-body">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="refuse-reason-{{ $post->getId() }}" placeholder="Indoklás" />
                            <label for="refuse-reason-{{ $post->getId() }}">Indoklás</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Mégse</button>
                        <button class="btn btn-outline-danger refuse-post" type="button" data-post-id="{{ $post->getId() }}" data-url="{{ route('admin.posts.refuse', ['postId' => $post->getId()]) }}">Elutasítás</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endpush
