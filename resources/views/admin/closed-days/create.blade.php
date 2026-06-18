@extends('layouts.admin')
@section('title', '定休日追加')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <a href="{{ route('admin.closed-days.index') }}" class="btn btn-outline-secondary mb-3">&larr; 戻る</a>
        <h1 class="mb-4">定休日追加</h1>
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.closed-days.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold fs-5">定休タイプ</label>
                        <select name="type" class="form-select form-select-lg" id="typeSelect">
                            <option value="weekly">毎週定休（曜日指定）</option>
                            <option value="specific">特定日</option>
                        </select>
                    </div>
                    <div class="mb-3" id="weeklyField">
                        <label class="form-label fw-bold fs-5">曜日</label>
                        <select name="day_of_week" class="form-select form-select-lg">
                            @foreach([0=>'日曜',1=>'月曜',2=>'火曜',3=>'水曜',4=>'木曜',5=>'金曜',6=>'土曜'] as $n => $l)
                                <option value="{{ $n }}">{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="specificField">
                        <label class="form-label fw-bold fs-5">日付</label>
                        <input type="date" name="date" class="form-control form-control-lg">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold fs-5">理由（備考）</label>
                        <input type="text" name="reason" class="form-control form-control-lg"
                               placeholder="例: 毎週月曜定休">
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg" type="submit">追加する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.getElementById('typeSelect').addEventListener('change', function() {
        document.getElementById('weeklyField').classList.toggle('d-none', this.value !== 'weekly');
        document.getElementById('specificField').classList.toggle('d-none', this.value !== 'specific');
    });
</script>
@endpush
@endsection
