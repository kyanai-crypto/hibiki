@extends('layouts.admin')
@section('title', 'ダッシュボード')
@section('content')
<h1 class="mb-4"><i class="bi bi-speedometer2"></i> ダッシュボード</h1>

<div class="row g-3 mb-5">
    <div class="col-6 col-md-3">
        <div class="card text-bg-warning text-center p-3 shadow-sm">
            <div class="fs-1 fw-bold">{{ $stats['pending'] }}</div>
            <div class="fs-5">申請中</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-bg-success text-center p-3 shadow-sm">
            <div class="fs-1 fw-bold">{{ $stats['today'] }}</div>
            <div class="fs-5">本日の出船</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-bg-primary text-center p-3 shadow-sm">
            <div class="fs-1 fw-bold">{{ $stats['approved'] }}</div>
            <div class="fs-5">7日内承認済</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-bg-info text-center p-3 shadow-sm">
            <div class="fs-1 fw-bold">{{ $stats['members'] }}</div>
            <div class="fs-5">会員数</div>
        </div>
    </div>
</div>

<h2 class="fs-4 mb-3"><i class="bi bi-clock-history text-warning"></i> 申請中の予約</h2>
@if($pendingReservations->isEmpty())
    <div class="alert alert-success">申請中の予約はありません。</div>
@else
    <div class="list-group mb-5">
        @foreach($pendingReservations as $r)
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <div class="fw-bold fs-5">{{ $r->user->name }} 様</div>
                        <div class="text-muted">{{ $r->reserved_date->format('Y年m月d日') }} {{ $r->tripTypeLabel }} {{ $r->num_people }}名</div>
                    </div>
                    <div class="d-flex gap-2">
                        <form method="POST" action="{{ route('admin.reservations.approve', $r) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-success btn-lg px-4">✅ 承認</button>
                        </form>
                        <a href="{{ route('admin.reservations.show', $r) }}" class="btn btn-outline-danger btn-lg">却下</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<h2 class="fs-4 mb-3"><i class="bi bi-calendar-check text-success"></i> 直近の承認済み予約</h2>
@if($upcomingReservations->isEmpty())
    <div class="alert alert-info">直近の予約はありません。</div>
@else
    <div class="list-group">
        @foreach($upcomingReservations as $r)
            <a href="{{ route('admin.reservations.show', $r) }}" class="list-group-item list-group-item-action">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="fw-bold">{{ $r->reserved_date->format('m/d') }} {{ $r->tripTypeLabel }}</div>
                        <div class="text-muted">{{ $r->user->name }} &middot; {{ $r->num_people }}名</div>
                    </div>
                    <span class="badge {{ $r->statusBadgeClass }}">{{ $r->statusLabel }}</span>
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
