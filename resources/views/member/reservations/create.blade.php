@extends('layouts.app')
@section('title', '予約申請')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h1 class="mb-4"><i class="bi bi-plus-circle"></i> 予約申請</h1>
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('reservations.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold fs-5">予約日 <span class="text-danger">*</span></label>
                        <input type="date" name="reserved_date"
                               class="form-control form-control-lg @error('reserved_date') is-invalid @enderror"
                               value="{{ old('reserved_date', $date) }}"
                               min="{{ today()->toDateString() }}" required>
                        @error('reserved_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold fs-5">便 <span class="text-danger">*</span></label>
                        <div class="d-grid gap-2">
                            @foreach(['morning' => '午前便', 'afternoon' => '午後便', 'night' => '夜便'] as $val => $label)
                                <div class="form-check form-check-lg border rounded p-3">
                                    <input class="form-check-input" type="radio" name="trip_type"
                                           id="trip_{{ $val }}" value="{{ $val }}"
                                           {{ old('trip_type', $tripType) === $val ? 'checked' : '' }} required>
                                    <label class="form-check-label fs-5 fw-bold" for="trip_{{ $val }}">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('trip_type')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold fs-5">人数 <span class="text-danger">*</span></label>
                        <select name="num_people" class="form-select form-select-lg @error('num_people') is-invalid @enderror" required>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ old('num_people', 1) == $i ? 'selected' : '' }}>{{ $i }}名</option>
                            @endfor
                        </select>
                        @error('num_people')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold fs-5">備考</label>
                        <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror"
                                  rows="3" placeholder="タックル・希望などあればご記入ください">{{ old('remarks') }}</textarea>
                        @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-lg" type="submit">予約を申請する</button>
                        <a href="{{ route('calendar.index') }}" class="btn btn-outline-secondary">戻る</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
