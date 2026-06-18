@extends('layouts.app')
@section('title', 'ログイン')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="text-center mb-4"><i class="bi bi-box-arrow-in-right"></i> ログイン</h2>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">メールアドレス</label>
                        <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">パスワード</label>
                        <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">ログインを保持する</label>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg" type="submit">ログイン</button>
                    </div>
                </form>
                <hr>
                <div class="text-center">
                    <a href="{{ route('register') }}"><i class="bi bi-person-plus"></i> 新規会員登録はこちら</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
