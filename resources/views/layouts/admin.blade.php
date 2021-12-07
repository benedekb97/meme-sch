<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Admin - @yield('title')</title>
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
    </head>
    <body>
        <input type="hidden" id="_token" value="{{ csrf_token() }}"/>


        <!-- SMALL SCREEN HEADER -->
        <nav class="navbar navbar-expand-md navbar-dark bg-dark show-md position-fixed top-0 w-100">
            <div class="container-fluid">
                <button class="navbar-toggler collapsed" type="button" data-bs-toggle="offcanvas" data-bs-target="#nav-offcanvas">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>


        <main>
            <!-- LARGE SCREEN NAV -->
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark hidden-md">
                <a href="{{ route('index') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    Meme.SCH
                </a>
                <hr/>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="{{ route('admin.index') }}" class="nav-link text-white @if(Route::currentRouteName() === 'admin.index'){{ 'active' }}@endif">
                            <i class="bi bi-house-door-fill"></i> Főoldal
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="position-relative nav-link text-white @if(\Illuminate\Support\Str::startsWith(Route::currentRouteName(), 'admin.groups')) active @endif" role="button" href="{{ route('admin.groups.index') }}">
                            <i class="bi bi-circle-half"></i> Körök
                        </a>
                    </li>
                    @if (Auth::user()->isAdministrator())
                        <li class="nav-item mt-3">
                            <a class="nav-link text-white @if(\Illuminate\Support\Str::startsWith(Route::currentRouteName(),'admin.users')) active @endif" role="button" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people"></i> Felhasználók
                            </a>
                        </li>
                    @endif
                    <li class="nav-item mt-3">
                        <a class="position-relative nav-link text-white @if(Route::currentRouteName() === 'admin.posts'){{ 'active' }}@endif" role="button" href="{{ route('admin.posts') }}">
                            <i class="bi bi-file-earmark-post"></i> Posztok
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="position-relative nav-link text-white @if(Route::currentRouteName() === 'admin.approvals'){{ 'active' }}@endif" role="button" href="{{ route('admin.approvals') }}">
                            <i class="bi bi-check-circle-fill"></i>
                            <div class="position-relative d-inline" style="left:3px;">
                                Elfogadásra váró posztok
                                @isset($unapprovedPostCount)
                                    <span class="badge rounded-pill bg-danger mx-1 @if($unapprovedPostCount === 0) visually-hidden @endif" id="unapproved-count-sidebar">
                                        {{ $unapprovedPostCount }}
                                    </span>
                                @endisset
                            </div>
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="position-relative nav-link text-white @if(Route::currentRouteName() === 'admin.refused-posts'){{ 'active' }}@endif" role="button" href="{{ route('admin.refused-posts') }}">
                            <i class="bi bi-trash-fill"></i>
                            <div class="position-relative d-inline" style="left:3px;" >
                                Elutasított posztok
                                @isset($refusedPostCount)
                                    <span class="badge rounded-pill bg-secondary mx-1 @if($refusedPostCount === 0) visually-hidden @endif" id="refused-count-sidebar">
                                        {{ $refusedPostCount }}
                                    </span>
                                @endisset
                            </div>
                        </a>
                    </li>
                </ul>
                <hr />
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <strong>{{ Auth::user()->getName() }}</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                        <li>
                            <a class="dropdown-item" href="{{ route('index') }}">Vissza a főoldalra</a>
                        </li>
                    </ul>
                </div>
            </div>


            <!-- MAIN CONTENT -->
            <div class="container-fluid mt-2 mb-3 scrollarea mt-md-3-inverse">
                <h1>@yield('page-title')</h1>
                <hr class="mt-1">
                @yield('content')
            </div>
        </main>

        <!-- SCRIPTS -->
        @stack('scripts')
        <script src="{{ asset('js/admin.js') }}"></script>



        <!-- OFFCANVAS -->
        <div class="offcanvas offcanvas-start bg-dark text-white d-flex flex-column flex-shrink-0" tabindex="-1" id="nav-offcanvas">
            <div class="offcanvas-header border-bottom">
                <a href="{{ route('index') }}" class="text-white text-decoration-none">
                    <h5 class="offcanvas-title">Meme.SCH</h5>
                </a>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column flex-shrink-0">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="{{ route('admin.index') }}" class="nav-link text-white @if(Route::currentRouteName() === 'admin.index'){{ 'active' }}@endif">
                            <i class="bi bi-house-door-fill"></i> Főoldal
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="nav-link text-white @if(\Illuminate\Support\Str::startsWith(Route::currentRouteName(), 'admin.groups')) active @endif" role="button" href="{{ route('admin.groups.index') }}">
                            <i class="bi bi-circle-half"></i> Körök
                        </a>
                    </li>
                    @if (Auth::user()->isAdministrator())
                        <li class="nav-item mt-3">
                            <a class="nav-link text-white @if(\Illuminate\Support\Str::startsWith(Route::currentRouteName(),'admin.users')) active @endif" role="button" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people"></i> Felhasználók
                            </a>
                        </li>
                    @endif
                    <li class="nav-item mt-3">
                        <a class="nav-link text-white @if(Route::currentRouteName() === 'admin.posts'){{ 'active' }}@endif" role="button" href="{{ route('admin.posts') }}">
                            <i class="bi bi-file-earmark-post"></i> Posztok
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="position-relative nav-link text-white @if(Route::currentRouteName() === 'admin.approvals'){{ 'active' }}@endif" role="button" href="{{ route('admin.approvals') }}">
                            <i class="bi bi-check-circle-fill"></i>
                            <div class="position-relative d-inline" style="left:3px;">
                                Elfogadásra váró posztok
                                @isset($unapprovedPostCount)
                                    <span class="badge rounded-pill bg-danger mx-1 @if($unapprovedPostCount === 0) visually-hidden @endif" id="unapproved-count-offcanvas">
                                        {{ $unapprovedPostCount }}
                                    </span>
                                @endisset
                            </div>
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="position-relative nav-link text-white @if(Route::currentRouteName() === 'admin.refused-posts'){{ 'active' }}@endif" role="button" href="{{ route('admin.refused-posts') }}">
                            <i class="bi bi-trash-fill"></i>
                            <div class="position-relative d-inline" style="left:3px;" >
                                Elutasított posztok
                                @isset($refusedPostCount)
                                    <span class="badge rounded-pill bg-secondary mx-1 @if($refusedPostCount === 0) visually-hidden @endif" id="refused-count-offcanvas">
                                        {{ $refusedPostCount }}
                                    </span>
                                @endisset
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <strong>{{ Auth::user()->getName() }}</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                        <li>
                            <a class="dropdown-item" href="{{ route('index') }}">Vissza a főoldalra</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <!-- TOASTS -->
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
        <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toast-container" style="z-index:11;">
            @stack('toasts')
        </div>

        @stack('modals')
    </body>
</html>
