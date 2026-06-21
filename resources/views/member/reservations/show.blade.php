@extends('layouts.app')
@section('title', '予約詳細')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary mb-3">&larr; 予約一覧</a>
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0 fs-4">予約詳細</h2>
                <span class="badge {{ $reservation->statusBadgeClass }} fs-6">{{ $reservation->statusLabel }}</span>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr><th class="w-40">予約日</th><td>{{ $reservation->reserved_date->format('Y年m月d日') }}</td></tr>
                    <tr><th>便</th><td>{{ $reservation->tripTypeLabel }}</td></tr>
                    <tr><th>人数</th><td>{{ $reservation->num_people }}名</td></tr>
                    <tr><th>備考</th><td>{{ $reservation->remarks ?? 'なし' }}</td></tr>
                    <tr><th>申請日時</th><td>{{ $reservation->created_at->format('Y/m/d H:i') }}</td></tr>
                    @if($reservation->isApproved() || $reservation->isRejected())
                        <tr><th>承認・却下日時</th><td>{{ $reservation->approved_at?->format('Y/m/d H:i') }}</td></tr>
                    @endif
                    @if($reservation->isRejected() && $reservation->reject_reason)
                        <tr><th>却下理由</th><td class="text-danger">{{ $reservation->reject_reason }}</td></tr>
                    @endif
                    @if($reservation->isCancelled() && $reservation->cancel_reason)
                        <tr><th>キャンセル理由</th><td class="text-warning">{{ $reservation->cancel_reason }}</td></tr>
                    @endif
                </table>
            </div>
            @if($reservation->isCancellable() && auth()->user()->isMember())
                <div class="card-footer">
                    <form method="POST" action="{{ route('reservations.cancel', $reservation) }}"
                          onsubmit="return confirm('この予約をキャンセルしますか？')">
                        @csrf @method('PATCH')
                        <button class="btn btn-danger btn-lg w-100">
                            <i class="bi bi-x-circle"></i> キャンセルする
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
