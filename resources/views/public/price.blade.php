@extends('layouts.app')
@section('title', '料金案内')
@section('content')
<h1><i class="bi bi-currency-yen"></i> 料金案内</h1>
<div class="card mt-3">
    <div class="card-body">
        @php $info = \App\Models\Setting::get('price_info'); @endphp
        @if($info)
            {!! $info !!}
        @else
            <p class="text-muted">料金案内は現在準備中です。お問い合わせください。</p>
        @endif
    </div>
</div>
@endsection
