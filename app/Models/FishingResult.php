<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FishingResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'result_date',
        'fish_type',
        'fish_size',
        'comment',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'result_date'  => 'date',
            'is_published' => 'boolean',
        ];
    }

    // ── リレーション ───────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(FishingResultImage::class)->orderBy('sort_order');
    }

    public function firstImage(): HasMany
    {
        return $this->hasMany(FishingResultImage::class)->orderBy('sort_order')->limit(1);
    }

    // ── スコープ ────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('result_date', 'desc');
    }
}
