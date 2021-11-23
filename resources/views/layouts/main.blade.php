<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>@yield('title')</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    </head>
    <body>
    @isset($group) <input type="hidden" id="groupId" value="{{ $group->getId() }}"/> @endisset
        <header class="p-3 bg-dark text-white">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                    <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                        <li>
                            <a href="{{ route('index') }}" class="nav-link px-2 text-white">Főoldal</a>
                        </li>
                        <li class="mx-2">
                            <div class="dropdown">
                                <a class="btn bg-opacity-10 btn-outline-light dropdown-toggle" href="#" role="button" id="groupDropdownLink" data-bs-toggle="dropdown">
                                    @isset($group) {{ $group->getName() }} @else Körök @endisset
                                </a>

                                <ul class="dropdown-menu">
                                    @foreach (Auth::user()->getGroupUsers() as $groupUser)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('groups.posts', ['groupId' => $groupUser->getGroup()->getId()]) }}">{{ $groupUser->getGroup()->getName() }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <div class="text-end mb-3 mb-lg-0 me-lg-3">
                        <button type="button" class="btn btn-outline-light me-2" data-bs-toggle="modal" data-bs-target="#new-post-modal">
                            <i class="bi bi-cloud-plus"></i>&nbsp;Új poszt
                        </button>
                    </div>
                    <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                        <input type="search" class="form-control form-control-dark" placeholder="Keresés..." aria-label="Keresés"/>
                    </form>
                    <div class="text-end">
                        @if (!Auth::check())
                            <a href="{{ route('auth.redirect') }}" class="btn btn-outline-light me-2">Bejelentkezés</a>
                        @else
                            <a href="{{ route('auth.logout') }}" class="btn btn-outline-light me-2">Kijelentkezés</a>
                            <a href="{{ route('profile') }}" class="btn btn-outline-light me-2">Profilom</a>
                            @if (Auth::user()->isAdministrator())
                                <a href="{{ route('admin.index') }}" class="btn btn-outline-warning me-2">Admin</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </header>
        <main class="container">
            @yield('content')
        </main>
        <div class="modal fade" id="new-post-modal" tabindex="-1" aria-labelledby="new-post-modal">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg modal-fullscreen-lg-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Új poszt létrehozása</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Bezárás"></button>
                    </div>
                    <div class="modal-body">
                        <form onkeydown="return event.key !== 'Enter';" action="{{ route('posts.create') }}" method="POST" enctype="multipart/form-data" id="new-post-form" novalidate>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="post-csrf-token"/>
                            <div class="mb-3 form-floating">
                                <input maxlength="255" type="text" class="form-control" id="post-title" placeholder="Poszt címe" name="title" required/>
                                <label for="post-title">Poszt címe</label>
                                <div class="invalid-feedback" id="post-title-invalid-feedback"></div>
                                <div class="valid-feedback" id="post-title-valid-feedback"></div>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="" id="post-anonymous" />
                                <label class="form-check-label" for="post-anonymous" data-bs-toggle="tooltip" data-bs-placement="top" title="Anonim feltöltés esetén egy adminnak el kell bírálnia a posztot mielőtt kikerül az oldalra!">
                                    Anonim feltöltés
                                </label>
                            </div>
                            <div class="mb-3">
                                <label for="post-file" class="form-label">Fájl kiválasztása</label>
                                <input required accept="image/jpeg, image/png, image/gif" type="file" class="form-control" id="post-file" name="file" onchange="loadFile(event)"/>
                                <div class="invalid-feedback" id="post-file-invalid-feedback"></div>
                                <div class="valid-feedback" id="post-file-valid-feedback"></div>
                            </div>
                            <div class="mb-3 text-center">
                                <img id="image-preview" src="" class="visually-hidden img-fluid" alt="Upload preview"/>
                            </div>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="post-group">Kör</label>
                                <select class="form-select" id="post-group">
                                    <option selected>Schönherz</option>
                                    @foreach (Auth::user()->getGroupUsers() as $groupUser)
                                        @if ($groupUser->canPost())
                                            <option value="{{ $groupUser->getGroup()->getId() }}" @isset($group) @if($group === $groupUser->getGroup()) selected @endif @endisset>{{ $groupUser->getGroup()->getName() }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Mégse</button>
                        <button class="btn btn-primary" type="button" onclick="submitNewPostForm(event)" id="new-post-save-button">Mentés</button>
                    </div>
                </div>
            </div>
        </div>
        <template id="toast-template">
            <div id="" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto"></strong>
                    <small>Épp most...</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Bezárás"></button>
                </div>
                <div class="toast-body">

                </div>
            </div>
        </template>
        <input type="hidden" id="create-comment-url" value="{{ route('comments.create') }}"/>
        <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toast-container" style="z-index:11;">
            @stack('toasts')
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
