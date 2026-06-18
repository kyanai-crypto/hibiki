@extends('layouts.app')
@section('title', '予約一覧')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1><i class="bi bi-list-check"></i> 予約一覧</h1>
    <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle"></i> 新規予約
    </a>
</div>

@if($reservations->isEmpty())
    <div class="alert alert-info">予約がありません。<a href="{{ route('calendar.index') }}">カレンダーから予約する</a></div>
@else
    <div class="list-group">
        @foreach($reservations as $r)
            <a href="{{ route('reservations.show', $r) }}" class="list-group-item list-group-item-action py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold fs-5">{{ $r->reserved_date->format('Y年m月d日') }}</div>
                        <div class="text-muted">{{ $r->tripTypeLabel }} &middot; {{ $r->num_people }}名</div>
                    </div>
                    <span class="badge {{ $r->statusBadgeClass }} fs-6">{{ $r->statusLabel }}</span>
                </div>
            </a>
        @endforeach
    </div>
    <div class="mt-3">{{ $reservations->links() }}</div>
@endif
@endsection
