@extends('layouts.admin')
@section('title', '釣果情報管理')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-fish"></i> 釣果情報</h1>
    <a href="{{ route('admin.fishing-results.create') }}" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle"></i> 新規登録
    </a>
</div>

<div class="row row-cols-1 row-cols-md-2 g-4">
    @forelse($results as $result)
        <div class="col">
            <div class="card h-100 shadow-sm">
                @if($result->images->isNotEmpty())
                    <img src="{{ Storage::url($result->images->first()->path) }}" class="card-img-top"
                         style="height:180px;object-fit:cover;">
                @endif
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title">{{ $result->fish_type }}</h5>
                        <span class="badge {{ $result->is_published ? 'bg-success' : 'bg-secondary' }}">
                            {{ $result->is_published ? '公開' : '非公開' }}
                        </span>
                    </div>
                    <p class="text-muted">{{ $result->result_date->format('Y年m月d日') }}</p>
                </div>
                <div class="card-footer d-flex gap-2">
                    <a href="{{ route('admin.fishing-results.edit', $result) }}" class="btn btn-outline-primary btn-sm flex-fill">編集</a>
                    <form method="POST" action="{{ route('admin.fishing-results.destroy', $result) }}"
                          onsubmit="return confirm('削除しますか？')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">削除</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col"><div class="alert alert-info">釣果情報はまだありません。</div></div>
    @endforelse
</div>
<div class="mt-4">{{ $results->links() }}</div>
@endsection
