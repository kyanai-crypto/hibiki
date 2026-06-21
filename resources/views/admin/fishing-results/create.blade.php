@extends('layouts.admin')
@section('title', '釣果登録')
@section('content')
<a href="{{ route('admin.fishing-results.index') }}" class="btn btn-outline-secondary mb-3">&larr; 戻る</a>
<h1 class="mb-4">釣果登録</h1>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.fishing-results.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold fs-5">釣果日 <span class="text-danger">*</span></label>
                    <input type="date" name="result_date" class="form-control form-control-lg"
                           value="{{ old('result_date', today()->toDateString()) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold fs-5">魚種 <span class="text-danger">*</span></label>
                    <input type="text" name="fish_type" class="form-control form-control-lg"
                           value="{{ old('fish_type') }}" placeholder="マダイ、イナダなど" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold fs-5">サイズ</label>
                    <input type="text" name="fish_size" class="form-control form-control-lg"
                           value="{{ old('fish_size') }}" placeholder="例: 50cmクラス">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold fs-5">コメント</label>
                    <textarea name="comment" class="form-control" rows="4">{{ old('comment') }}</textarea>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold fs-5">写真（複数枚可）</label>
                    <input type="file" name="images[]" class="form-control form-control-lg"
                           accept="image/*" multiple>
                    <div class="form-text">最大10枚、各5MBまで</div>
                </div>
                <div class="col-12 mb-4">
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" name="is_published" value="1"
                               id="is_published" checked>
                        <label class="form-check-label fs-5" for="is_published">公開する</label>
                    </div>
                </div>
            </div>
            <div class="d-grid">
                <button class="btn btn-primary btn-lg" type="submit">登録する</button>
            </div>
        </form>
    </div>
</div>
@endsection
