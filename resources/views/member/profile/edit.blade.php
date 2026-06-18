@extends('layouts.app')
@section('title', 'プロフィール編集')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h1 class="mb-4"><i class="bi bi-person"></i> プロフィール編集</h1>

        {{-- 基本情報 --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header fw-bold">基本情報</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('member.profile.update') }}">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label fw-bold">氏名</label>
                        <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">電話番号</label>
                        <input type="tel" name="phone" class="form-control form-control-lg @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $user->phone) }}" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">メールアドレス</label>
                        <input type="text" class="form-control form-control-lg bg-light" value="{{ $user->email }}" disabled>
                        <div class="form-text">メールアドレスの変更はお問い合わせください。</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">住所</label>
                        <input type="text" name="address" class="form-control form-control-lg @error('address') is-invalid @enderror"
                               value="{{ old('address', $user->address) }}" required>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg" type="submit">保存する</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- パスワード変更 --}}
        <div class="card shadow-sm">
            <div class="card-header fw-bold">パスワード変更</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('member.profile.password') }}">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label fw-bold">現在のパスワード</label>
                        <input type="password" name="current_password"
                               class="form-control form-control-lg @error('current_password') is-invalid @enderror" required>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">新しいパスワード</label>
                        <input type="password" name="password"
                               class="form-control form-control-lg @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">新しいパスワード（確認）</label>
                        <input type="password" name="password_confirmation" class="form-control form-control-lg" required>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-warning btn-lg" type="submit">パスワードを変更する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
