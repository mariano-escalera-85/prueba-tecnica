<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-light text-dark min-vh-100 d-flex flex-column">
        <header class="container py-3">
            @if (Route::has('login'))
                <nav class="d-flex justify-content-end gap-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <main class="container my-auto py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex align-items-center mb-4">
                                <h1 class="h3 fw-bold mb-0">Let's get started</h1>
                            </div>
                            <p class="text-secondary mb-4">
                                With so many options available to you, we suggest you start with the following:
                            </p>
                            <div class="list-group list-group-flush mb-4">
                                <a href="https://laravel.com/docs" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between px-0 py-3 border-bottom">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-book text-danger fs-5"></i>
                                        <span class="fw-semibold">Documentation</span>
                                    </div>
                                    <i class="bi bi-arrow-up-right text-muted"></i>
                                </a>
                                <a href="https://laracasts.com" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between px-0 py-3 border-bottom">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-play-circle text-danger fs-5"></i>
                                        <span class="fw-semibold">Laracasts</span>
                                    </div>
                                    <i class="bi bi-arrow-up-right text-muted"></i>
                                </a>
                                <a href="https://cloud.laravel.com" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between px-0 py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-cloud text-danger fs-5"></i>
                                        <span class="fw-semibold">Deploy now</span>
                                    </div>
                                    <i class="bi bi-arrow-up-right text-muted"></i>
                                </a>
                            </div>

                            <div class="d-flex align-items-center justify-content-between pt-3 border-top text-muted small">
                                <span>Laravel v{{ app()->version() }} (PHP v{{ PHP_VERSION }})</span>
                                <a href="https://github.com/laravel/framework/blob/13.x/CHANGELOG.md" target="_blank" class="text-decoration-none text-danger fw-medium">
                                    View changelog <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
