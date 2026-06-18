<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'reserved_date',
        'trip_type',
        'num_people',
        'remarks',
        'status',
        'approved_by',
        'approved_at',
        'reject_reason',
        'cancelled_by',
        'cancel_reason',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'reserved_date' => 'date',
            'approved_at'   => 'datetime',
            'cancelled_at'  => 'datetime',
        ];
    }

    // ステータス定数
    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    // 便定数
    const TRIP_MORNING   = 'morning';
    const TRIP_AFTERNOON = 'afternoon';
    const TRIP_NIGHT     = 'night';

    // ── リレーション ───────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── アクセサ ────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING   => '申請中',
            self::STATUS_APPROVED  => '承認済',
            self::STATUS_REJECTED  => '却下',
            self::STATUS_CANCELLED => 'キャンセル',
            self::STATUS_COMPLETED => '出船完了',
            default                => '不明',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING   => 'bg-warning text-dark',
            self::STATUS_APPROVED  => 'bg-success',
            self::STATUS_REJECTED  => 'bg-danger',
            self::STATUS_CANCELLED => 'bg-secondary',
            self::STATUS_COMPLETED => 'bg-primary',
            default                => 'bg-light text-dark',
        };
    }

    public function getTripTypeLabelAttribute(): string
    {
        return match ($this->trip_type) {
            self::TRIP_MORNING   => '午前便',
            self::TRIP_AFTERNOON => '午後便',
            self::TRIP_NIGHT     => '夜便',
            default              => '不明',
        };
    }

    // ── スコープ ────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED]);
    }

    public function scopeForDate($query, string $date)
    {
        return $query->where('reserved_date', $date);
    }

    public function scopeForTrip($query, string $tripType)
    {
        return $query->where('trip_type', $tripType);
    }

    // ── 状態チェック ────────────────────────────────

    public function isPending(): bool    { return $this->status === self::STATUS_PENDING; }
    public function isApproved(): bool   { return $this->status === self::STATUS_APPROVED; }
    public function isRejected(): bool   { return $this->status === self::STATUS_REJECTED; }
    public function isCancelled(): bool  { return $this->status === self::STATUS_CANCELLED; }
    public function isCompleted(): bool  { return $this->status === self::STATUS_COMPLETED; }
    public function isCancellable(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED]);
    }
}
