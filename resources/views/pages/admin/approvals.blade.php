@extends('layouts.admin')

@section('title', 'Approvals')

@section('page-title', 'Elfogadásra váró posztok')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-12 mb-3">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Szűrés</span>
                </div>
                <div class="list-group">
                    <button type="button" class="list-group-item list-group-item-action dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#group-filters">
                        Körök <span class="badge bg-secondary" id="group-filters-selected-count"></span>
                    </button>
                    <div class="collapse" id="group-filters">
                        <div class="list-group">
                            @foreach ($groups as $group)
                                <a href="#" class="list-group-item group-filter" data-select-group-id="{{ $group === null ? 'null' : $group->getId() }}">{{ $group === null ? 'Schönherz' : $group->getName() }}</a>
                            @endforeach
                        </div>
                    </div>
                    <button type="button" class="list-group-item list-group-item-action dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#user-filters">
                        Felhasználók <span class="badge bg-secondary" id="user-filters-selected-count"></span>
                    </button>
                    <div class="collapse" id="user-filters">
                        <div class="list-group">
                            @foreach ($users as $userFilter)
                                <a href="#" class="list-group-item user-filter" data-select-user-id="{{ $userFilter->getId() }}">{{ $userFilter->getName() }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="row">
                @foreach ($posts as $post)
                    <div class="col-lg-6 mb-3 col-xl-4 post" id="post-{{ $post->getId() }}" data-group-id="{{ $post->hasGroup() ? $post->getGroup()->getId() : 'null' }}" data-user-id="{{ $post->getUser()->getId() }}">
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
                            @if ($post->getImage()->hasSourceSet())
                                <picture>
                                    @foreach ($post->getImage()->getSourceSet() as $width => $source)
                                        <source srcset="{{ route('posts.image.source', ['postId' => $post->getId(), 'width' => $width]) }}" media="(max-width:{{ $width }}px)"/>
                                    @endforeach
                                    <img src="{{ route('posts.image', ['postId' => $post->getId()]) }}" class="card-img-bottom" alt="{{ $post->getName() }}"/>
                                </picture>
                            @else
                                <img src="{{ route('posts.image', ['postId' => $post->getId()]) }}" class="card-img-bottom" alt="{{ $post->getName() }}">
                            @endif
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
        </div>
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

@push('scripts')
    <script>
        window.allUsers = @json($users->map(fn ($u) => $u->getId())->toArray());
        window.allGroups = @json($groups->map(fn ($g) => $g === null ? 'null' : $g->getId())->toArray());
    </script>
@endpush
