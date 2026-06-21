@extends('layouts.app')
@section('title', $fishingResult->fish_type . ' ' . $fishingResult->result_date->format('m/d'))
@section('content')
<a href="{{ route('fishing-results.index') }}" class="btn btn-outline-secondary mb-3">&larr; 釣果一覧</a>

<div class="card shadow-sm">
    <div class="card-body">
        <h1>{{ $fishingResult->fish_type }}</h1>
        <p class="text-muted">{{ $fishingResult->result_date->format('Y年m月d日') }}</p>
        @if($fishingResult->fish_size)
            <p><i class="bi bi-rulers"></i> サイズ: {{ $fishingResult->fish_size }}</p>
        @endif
        @if($fishingResult->comment)
            <p>{{ $fishingResult->comment }}</p>
        @endif
    </div>
</div>

@if($fishingResult->images->isNotEmpty())
    <div class="row g-3 mt-2">
        @foreach($fishingResult->images as $image)
            <div class="col-6 col-md-4">
                <a href="{{ Storage::url($image->path) }}" target="_blank">
                    <img src="{{ Storage::url($image->path) }}" class="img-fluid rounded shadow-sm"
                         style="width:100%;height:200px;object-fit:cover;" alt="">
                </a>
            </div>
        @endforeach
    </div>
@endif
@endsection
