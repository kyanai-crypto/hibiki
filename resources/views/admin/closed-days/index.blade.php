@extends('layouts.admin')
@section('title', '定休日管理')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-calendar-x"></i> 定休日管理</h1>
    <a href="{{ route('admin.closed-days.create') }}" class="btn btn-primary btn-lg">
        <i class="bi bi-plus"></i> 追加
    </a>
</div>

<h2 class="fs-5 mb-2">毎週定休日</h2>
<div class="list-group mb-4">
    @forelse($weeklyDays as $d)
        <div class="list-group-item d-flex justify-content-between align-items-center">
            <span class="fs-5">毎週{{ \App\Models\ClosedDay::$dayLabels[$d->day_of_week] }}曜日
                @if($d->reason)<small class="text-muted">({{ $d->reason }})</small>@endif
            </span>
            <form method="POST" action="{{ route('admin.closed-days.destroy', $d) }}"
                  onsubmit="return confirm('削除しますか？')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">削除</button>
            </form>
        </div>
    @empty
        <div class="list-group-item text-muted">登録なし</div>
    @endforelse
</div>

<h2 class="fs-5 mb-2">特定日休業</h2>
<div class="list-group">
    @forelse($specificDays as $d)
        <div class="list-group-item d-flex justify-content-between align-items-center">
            <span class="fs-5">{{ $d->date->format('Y年m月d日') }}
                @if($d->reason)<small class="text-muted">({{ $d->reason }})</small>@endif
            </span>
            <form method="POST" action="{{ route('admin.closed-days.destroy', $d) }}"
                  onsubmit="return confirm('削除しますか？')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">削除</button>
            </form>
        </div>
    @empty
        <div class="list-group-item text-muted">登録なし</div>
    @endforelse
</div>
@endsection
