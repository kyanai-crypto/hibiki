<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class FishingResultImage extends Model
{
    protected $fillable = [
        'fishing_result_id',
        'path',
        'sort_order',
    ];

    // ── リレーション ───────────────────────────────

    public function fishingResult(): BelongsTo
    {
        return $this->belongsTo(FishingResult::class);
    }

    // ── アクセサ ────────────────────────────────────

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }
}
