<div class="row mt-3 justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <a class="text-decoration-none text-black" href="{{ route('posts.show', ['postId' => $post->getId()]) }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-0">
                            {{ $post->getName() }}
                        </h5>
                        <div>
                            <span class="badge bg-secondary">@if ($post->hasGroup()) {{ $post->getGroup()->getName() }} @else Schönherz @endif</span>
                        </div>
                    </div>
                </div>
                @if ($post->getImage()->hasSourceSet())
                    <picture>
                        @foreach ($post->getImage()->getSourceSet() as $width => $source)
                            <source srcset="{{ route('posts.image.source', ['postId' => $post->getId(), 'width' => $width]) }}" media="(max-width:{{ $width }}px)">
                        @endforeach
                        <img alt="{{ $post->getName() }}" class="mx-auto d-block img-fluid mb-0 mt-0 card-img-bottom" src="{{ route('posts.image.source', ['postId' => $post->getId(), 'width' => $width]) }}"/>
                    </picture>
                @else
                    <img alt="{{ $post->getName() }}" class="mx-auto d-block img-fluid mb-0 mt-0 card-img-bottom" src="{{ route('posts.image', ['postId' => $post->getId()]) }}"/>
                @endif
            </a>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="text-muted @if($post->isAnonymous()) {{ 'fst-italic' }} @endif">
                        @if (!$post->isAnonymous() && $post->getUser()->getProfilePicture() !== null)
                            @if ($post->getUser()->getProfilePicture()->hasSourceSet())
                                <picture>
                                    @foreach ($post->getUser()->getProfilePicture()->getSourceSet() as $width => $source)
                                        <source srcset="{{ route('image.source', ['imageId' => $post->getUser()->getProfilePicture()->getId(), 'width' => $width]) }}" media="(max-width:{{ $width }}px)">
                                    @endforeach
                                    <img alt="{{ $post->getName() }}" class="rounded" style="max-height:32px; margin-right:3px;" src="{{ route('image', ['imageId' => $post->getUser()->getProfilePicture()->getId()]) }}"/>
                                </picture>
                            @else
                                <img src="{{ route('image', ['imageId' => $post->getUser()->getProfilePicture()->getId()]) }}" style="max-height:32px; margin-right:3px;" class="rounded" alt="{{ $post->getUser()->getName() }}">
                            @endif
                        @endif
                        {{ !$post->isAnonymous() ? $post->getUser()->getNickName() ?? $post->getUser()->getName() : 'Anonymous' }}
                    </div>
                    <div>
                        @if (!$post->hasReportByUser($user))
                            <span id="report-post-container-{{ $post->getId() }}" title="Jelentés" data-bs-toggle="tooltip">
                                <button id="flag-post-button-{{ $post->getId() }}" type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#report-post-{{ $post->getId() }}">
                                    <i id="flag-post-{{ $post->getId() }}" class="bi bi-flag"></i>
                                </button>
                            </span>
                        @else
                            <button type="button" class="btn btn-light" data-bs-toggle="tooltip" title="Jelentve!">
                                <i class="bi bi-flag-fill"></i>
                            </button>
                        @endif
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
        </div>
    </div>
</div>
@if (!$post->hasReportByUser($user))
    <div class="modal fade" id="report-post-{{ $post->getId() }}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Poszt jelentése</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form onkeydown="return event.key !== 'Enter';" action="{{ route('report') }}" method="POST" id="report-post-form" novalidate>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="post-csrf-token"/>
                    <div class="modal-body">
                        <p>Miért szeretnéd reportolni a posztot?</p>
                        @foreach (\App\Entities\ReportInterface::REASON_MAP as $reason => $name)
                            <div class="form-check mx-3">
                                <input type="radio" name="reason-{{ $post->getId() }}" class="form-check-input" id="{{ $name }}" value="{{ $name }}">
                                <label for="{{ $name }}" class="form-check-label">{{ $reason }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button" onclick="submitReportForm(event)" data-post-id="{{ $post->getId() }}">Jelentés!</button>
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Mégse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
