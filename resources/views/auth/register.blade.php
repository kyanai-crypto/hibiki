@extends('layouts.app')
@section('title', '会員登録')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="text-center mb-4"><i class="bi bi-person-plus"></i> 新規会員登録</h2>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">氏名 <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">電話番号 <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control form-control-lg @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}" placeholder="090-0000-0000" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">メールアドレス <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">住所 <span class="text-danger">*</span></label>
                        <input type="text" name="address" class="form-control form-control-lg @error('address') is-invalid @enderror"
                               value="{{ old('address') }}" required>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">パスワード <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">パスワード（確認） <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control form-control-lg" required>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg" type="submit">登録する</button>
                    </div>
                </form>
                <hr>
                <div class="text-center">
                    <a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> すでに会員の方はこちら</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
