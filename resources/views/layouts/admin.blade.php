<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>管理画面 | @yield('title', config('app.name'))</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')
    <style>
        body { font-size: 1.1rem; background: #f8f9fa; }
        .sidebar { min-height: calc(100vh - 56px); background: #212529; }
        .sidebar .nav-link { color: #adb5bd; font-size: 1.05rem; padding: .75rem 1rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.1); }
        .sidebar .nav-link i { width: 1.5rem; }
        .main-content { min-height: calc(100vh - 56px); }
        .btn { font-size: 1rem; }
        /* スマホ: サイドバーを折りたたみ */
        @media (max-width: 767px) {
            .sidebar { min-height: auto; }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}"><i class="bi bi-anchor"></i> 管理画面</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}" target="_blank"><i class="bi bi-house"></i> 公開ページ</a></li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="nav-link btn btn-link text-white" type="submit"><i class="bi bi-box-arrow-right"></i> ログアウト</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar py-3">
            <ul class="nav flex-column">
                <li><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> ダッシュボード</a></li>
                <li><a class="nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}" href="{{ route('admin.reservations.index') }}"><i class="bi bi-calendar-check"></i> 予約管理</a></li>
                <li class="mt-2 px-3 text-secondary small">カレンダー設定</li>
                <li><a class="nav-link {{ request()->routeIs('admin.closed-days.*') ? 'active' : '' }}" href="{{ route('admin.closed-days.index') }}"><i class="bi bi-calendar-x"></i> 定休日管理</a></li>
                <li><a class="nav-link {{ request()->routeIs('admin.business-days.*') ? 'active' : '' }}" href="{{ route('admin.business-days.index') }}"><i class="bi bi-calendar2-week"></i> 営業日設定</a></li>
                <li class="mt-2 px-3 text-secondary small">コンテンツ</li>
                <li><a class="nav-link {{ request()->routeIs('admin.fishing-results.*') ? 'active' : '' }}" href="{{ route('admin.fishing-results.index') }}"><i class="bi bi-fish"></i> 釣果情報</a></li>
                <li class="mt-2 px-3 text-secondary small">システム</li>
                <li><a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}"><i class="bi bi-gear"></i> システム設定</a></li>
            </ul>
        </nav>

        <main class="col-md-9 col-lg-10 main-content p-4">
            @include('components.alerts')
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
