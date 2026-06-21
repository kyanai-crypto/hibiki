@extends('layouts.app')
@section('title', 'メール認証')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4 text-center">
                <i class="bi bi-envelope-check display-3 text-primary"></i>
                <h2 class="mt-3">メールアドレスの認証</h2>
                <p class="mt-3">登録いただいたメールアドレスに認証メールを送信しました。<br>メール内のリンクから認証を完了してください。</p>
                @if(session('status'))
                    <div class="alert alert-success mt-3">{{ session('status') }}</div>
                @endif
                <form method="POST" action="{{ route('verification.send') }}" class="mt-3">
                    @csrf
                    <button class="btn btn-outline-primary">認証メールを再送する</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
