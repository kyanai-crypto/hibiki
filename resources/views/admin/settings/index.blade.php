@extends('layouts.admin')
@section('title', 'システム設定')
@section('content')
<h1 class="mb-4"><i class="bi bi-gear"></i> システム設定</h1>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf @method('PATCH')

            <h5 class="border-bottom pb-2 mb-3">基本設定</h5>
            <div class="mb-3">
                <label class="form-label fw-bold fs-5">サイト名</label>
                <input type="text" name="site_name" class="form-control form-control-lg"
                       value="{{ old('site_name', $settings['site_name']?->value) }}" required>
            </div>

            <h5 class="border-bottom pb-2 mb-3 mt-4">定員設定</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold fs-5">基本定員（名）</label>
                    <input type="number" name="default_capacity" class="form-control form-control-lg"
                           value="{{ old('default_capacity', $settings['default_capacity']?->value) }}"
                           min="1" max="99" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold fs-5">残りわずか（△）閾値（%）</label>
                    <input type="number" name="capacity_threshold_few" class="form-control form-control-lg"
                           value="{{ old('capacity_threshold_few', $settings['capacity_threshold_few']?->value) }}"
                           min="1" max="99" required>
                    <div class="form-text">この割合以上で「残りわずか」表示</div>
                </div>
            </div>

            <h5 class="border-bottom pb-2 mb-3 mt-4">デフォルト営業便</h5>
            <div class="d-flex gap-4 mb-3 flex-wrap">
                @foreach(['morning_open' => '午前便', 'afternoon_open' => '午後便', 'night_open' => '夜便'] as $key => $label)
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="{{ $key }}" value="1"
                               id="{{ $key }}" {{ $settings[$key]?->value ? 'checked' : '' }}>
                        <label class="form-check-label fs-5" for="{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
            </div>

            <h5 class="border-bottom pb-2 mb-3 mt-4">料金案内</h5>
            <div class="mb-4">
                <textarea name="price_info" class="form-control" rows="6"
                          placeholder="HTMLも使用可能です">{{ old('price_info', $settings['price_info']?->value) }}</textarea>
            </div>

            <div class="d-grid">
                <button class="btn btn-primary btn-lg" type="submit">保存する</button>
            </div>
        </form>
    </div>
</div>
@endsection
