@extends('layouts.app')
@section('title', '釣果情報')
@section('content')
<h1 class="mb-4"><i class="bi bi-fish"></i> 釣果情報</h1>

@if($results->isEmpty())
    <div class="alert alert-info">釣果情報はまだありません。</div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($results as $result)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    @if($result->images->isNotEmpty())
                        <img src="{{ Storage::url($result->images->first()->path) }}"
                             class="card-img-top" style="height:200px;object-fit:cover;"
                             alt="{{ $result->fish_type }}">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height:200px;">
                            <i class="bi bi-image text-muted" style="font-size:3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $result->fish_type }}</h5>
                        <p class="text-muted small">{{ $result->result_date->format('Y年m月d日') }}</p>
                        @if($result->fish_size)
                            <p class="mb-1"><i class="bi bi-rulers"></i> {{ $result->fish_size }}</p>
                        @endif
                        @if($result->comment)
                            <p class="card-text small">{{ Str::limit($result->comment, 80) }}</p>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('fishing-results.show', $result) }}" class="btn btn-outline-primary btn-sm w-100">詳細を見る</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $results->links() }}</div>
@endif
@endsection
