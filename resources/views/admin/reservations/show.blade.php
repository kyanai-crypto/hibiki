@extends('layouts.admin')
@section('title', '予約詳細')
@section('content')
<a href="{{ route('admin.reservations.index') }}" class="btn btn-outline-secondary mb-3">&larr; 予約一覧</a>

<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mb-0 fs-4">予約 #{{ $reservation->id }}</h2>
        <span class="badge {{ $reservation->statusBadgeClass }} fs-5">{{ $reservation->statusLabel }}</span>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr><th>会員名</th><td>{{ $reservation->user->name }} 様</td></tr>
            <tr><th>電話番号</th><td>{{ $reservation->user->phone }}</td></tr>
            <tr><th>予約日</th><td>{{ $reservation->reserved_date->format('Y年m月d日') }}</td></tr>
            <tr><th>便</th><td>{{ $reservation->tripTypeLabel }}</td></tr>
            <tr><th>人数</th><td>{{ $reservation->num_people }}名</td></tr>
            <tr><th>備考</th><td>{{ $reservation->remarks ?? 'なし' }}</td></tr>
            <tr><th>申請日時</th><td>{{ $reservation->created_at->format('Y/m/d H:i') }}</td></tr>
            @if($reservation->approved_at)
                <tr><th>承認・却下日時</th><td>{{ $reservation->approved_at->format('Y/m/d H:i') }} ({{ $reservation->approver?->name }})</td></tr>
            @endif
            @if($reservation->reject_reason)
                <tr><th>却下理由</th><td class="text-danger">{{ $reservation->reject_reason }}</td></tr>
            @endif
            @if($reservation->cancel_reason)
                <tr><th>キャンセル理由</th><td>{{ $reservation->cancel_reason }} (区分: {{ $reservation->cancelled_by }})</td></tr>
            @endif
        </table>
    </div>
</div>

{{-- 承認・却下 --}}
@if($reservation->isPending())
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <form method="POST" action="{{ route('admin.reservations.approve', $reservation) }}">
                @csrf @method('PATCH')
                <button class="btn btn-success btn-lg w-100">✅ 承認する</button>
            </form>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.reservations.reject', $reservation) }}">
                        @csrf @method('PATCH')
                        <div class="mb-2">
                            <textarea name="reject_reason" class="form-control" rows="2" placeholder="却下理由（任b意）"></textarea>
                        </div>
                        <button class="btn btn-danger w-100">❌ 却下する</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- 管理者キャンセル --}}
@if($reservation->isCancellable())
    <div class="card border-danger">
        <div class="card-header text-danger fw-bold">運定側キャンセル</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.reservations.cancel', $reservation) }}"
                  onsubmit="return confirm('キャンセルしてよろしいですか？会員にメールで通知されます。')">
                @csrf @method('PATCH')
                <div class="mb-2">
                    <label class="form-label fw-bold">キャンセル理由 <span class="text-danger">*</span></label>
                    <input type="text" name="cancel_reason" class="form-control form-control-lg"
                           placeholder="例: 悪天候のため出船中止" required>
                </div>
                <button class="btn btn-danger btn-lg w-100">運定側キャンセルする</button>
            </form>
        </div>
    </div>
@endif

{{-- 出船完了 --}}
@if($reservation->isApproved())
    <div class="mt-3">
        <form method="POST" action="{{ route('admin.reservations.complete', $reservation) }}"
              onsubmit="return confirm('出船完了にしますか？')">
            @csrf @method('PATCH')
            <button class="btn btn-primary btn-lg w-100">⚓ 出船完了にする</button>
        </form>
    </div>
@endif
@endsection
