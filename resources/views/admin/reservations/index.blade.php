@extends('layouts.admin')
@section('title', '予約管理')
@section('content')
<h1 class="mb-4"><i class="bi bi-calendar-check"></i> 予約管理</h1>

<form class="row g-2 mb-4" method="GET">
    <div class="col-auto">
        <select name="status" class="form-select form-select-lg">
            <option value="">すべてのステータス</option>
            @foreach(['pending' => '申請中', 'approved' => '承認済', 'rejected' => '却下', 'cancelled' => 'キャンセル', 'completed' => '出船完了'] as $val => $label)
                <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <input type="date" name="date" class="form-control form-select-lg" value="{{ request('date') }}">
    </div>
    <div class="col-auto d-flex gap-2">
        <button class="btn btn-primary btn-lg">絞り込み</button>
        <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline-secondary btn-lg">リセット</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th><th>会員名</th><th>予約日</th><th>便</th><th>人数</th><th>ステータス</th><th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->user->name }}</td>
                    <td>{{ $r->reserved_date->format('Y/m/d') }}</td>
                    <td>{{ $r->tripTypeLabel }}</td>
                    <td>{{ $r->num_people }}名</td>
                    <td><span class="badge {{ $r->statusBadgeClass }}">{{ $r->statusLabel }}</span></td>
                    <td><a href="{{ route('admin.reservations.show', $r) }}" class="btn btn-sm btn-outline-primary">詳細</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">予約がありません</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $reservations->links() }}</div>
@endsection
