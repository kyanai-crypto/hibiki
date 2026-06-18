<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')
    <style>
        body { font-size: 1.05rem; }
        .navbar-brand { font-size: 1.3rem; font-weight: bold; }
        .btn { font-size: 1rem; padding: .55rem 1.1rem; }
        .table td, .table th { vertical-align: middle; }
        /* スマホ対応: タップターゲットを大きめに */
        @media (max-width: 767px) {
            .btn { padding: .65rem 1.2rem; }
            h1 { font-size: 1.6rem; }
            h2 { font-size: 1.3rem; }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}"><i class="bi bi-anchor"></i> {{ \App\Models\Setting::get('site_name', config('app.name')) }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('calendar.index') }}"><i class="bi bi-calendar3"></i> 空き状況</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('fishing-results.index') }}"><i class="bi bi-fish"></i> 釣果情報</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('price') }}"><i class="bi bi-currency-yen"></i> 料金案内</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                @auth
                    @if(auth()->user()->isMaster())
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-gear"></i> 管理画面</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('reservations.index') }}"><i class="bi bi-list-check"></i> 予約一覧</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('member.profile.edit') }}"><i class="bi bi-person"></i> プロフィール</a></li>
                    @endif
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="nav-link btn btn-link" type="submit"><i class="bi bi-box-arrow-right"></i> ログアウト</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> ログイン</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus"></i> 会員登録</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    @include('components.alerts')
    @yield('content')
</main>

<footer class="bg-light py-3 mt-5 border-top">
    <div class="container text-center text-muted small">
        &copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', config('app.name')) }}
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
