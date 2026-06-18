@extends('layouts.app')
@section('title', '空き状況カレンダー')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <h1 class="mb-0"><i class="bi bi-calendar3"></i> 空き状況</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('calendar.index', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}"
           class="btn btn-outline-secondary">&lsaquo; 前月</a>
        <span class="btn btn-light fw-bold">{{ $year }}年{{ $month }}月</span>
        <a href="{{ route('calendar.index', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}"
           class="btn btn-outline-secondary">翌月 &rsaquo;</a>
    </div>
</div>

{{-- 列説 --}}
<div class="d-flex gap-3 mb-3 flex-wrap">
    <span class="badge bg-success fs-6">○ 予約可</span>
    <span class="badge bg-warning text-dark fs-6">△ 残りわずか</span>
    <span class="badge bg-danger fs-6">× 満席</span>
    <span class="badge bg-secondary fs-6">休 休船</span>
</div>

<div class="table-responsive">
<table class="table table-bordered text-center calendar-table">
    <thead class="table-dark">
        <tr>
            <th class="text-danger">日</th>
            <th>月</th>
            <th>火</th>
            <th>水</th>
            <th>木</th>
            <th>金</th>
            <th class="text-primary">土</th>
        </tr>
    </thead>
    <tbody>
    @php
        use Carbon\Carbon;
        $firstDay = Carbon::create($year, $month, 1);
        $startDow = $firstDay->dayOfWeek; // 0=日
        $daysInMonth = $firstDay->daysInMonth;
        $day = 1;
    @endphp
    @for($row = 0; $row < 6; $row++)
        @php if($day > $daysInMonth) break; @endphp
        <tr>
        @for($col = 0; $col < 7; $col++)
            @php
                $isBlank = ($row === 0 && $col < $startDow) || $day > $daysInMonth;
                if (!$isBlank) {
                    $dateStr = Carbon::create($year, $month, $day)->toDateString();
                    $info = $calendarData->get($dateStr);
                }
            @endphp
            @if($isBlank)
                <td class="bg-light"></td>
            @else
                @php
                    $isToday = $dateStr === now()->toDateString();
                    $isHoliday = ($info['status'] ?? '') === 'holiday';
                    $isClosed = ($info['status'] ?? '') === 'closed';
                    $tdClass = $isToday ? 'table-info' : ($col === 0 ? 'table-danger bg-opacity-10' : ($col === 6 ? 'table-primary bg-opacity-10' : ''));
                @endphp
                <td class="{{ $tdClass }}" style="min-width:60px;">
                    <div class="fw-bold {{ $isToday ? 'text-primary' : '' }}">{{ $day }}</div>
                    @if($isHoliday)
                        <span class="badge bg-secondary">休</span>
                    @elseif($isClosed)
                        <span class="text-muted small">&mdash;</span>
                    @else
                        @foreach($info['trips'] ?? [] as $trip => $avail)
                            @php
                                $label = match($trip) { 'morning' => '午前', 'afternoon' => '午後', 'night' => '夜', default => $trip };
                                $badgeClass = match($avail['icon']) { '○' => 'bg-success', '△' => 'bg-warning text-dark', '×' => 'bg-danger', default => 'bg-secondary' };
                            @endphp
                            <div>
                                <small class="text-muted">{{ $label }}</small>
                                @auth
                                    @if($avail['icon'] !== '×')
                                        <a href="{{ route('reservations.create', ['date' => $dateStr, 'trip_type' => $trip]) }}"
                                           class="badge {{ $badgeClass }} text-decoration-none">{{ $avail['icon'] }}</a>
                                    @else
                                        <span class="badge {{ $badgeClass }}">{{ $avail['icon'] }}</span>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="badge {{ $badgeClass }} text-decoration-none">{{ $avail['icon'] }}</a>
                                @endauth
                            </div>
                        @endforeach
                    @endif
                </td>
                @php $day++; @endphp
            @endif
        @endfor
        </tr>
    @endfor
    </tbody>
</table>
</div>

@auth
    <div class="text-center mt-3">
        <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle"></i> 予約する
        </a>
    </div>
@else
    <div class="alert alert-info text-center mt-3">
        <i class="bi bi-info-circle"></i> 予約には<a href="{{ route('login') }}」>ログイン</a>または<a href="{{ route('register') }}">会員登録</a>が必要です。
    </div>
@endauth
@endsection
