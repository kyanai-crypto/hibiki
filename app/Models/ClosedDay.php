<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ClosedDay extends Model
{
    protected $fillable = [
        'type',
        'day_of_week',
        'date',
        'reason',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'date'      => 'date',
            'is_active' => 'boolean',
        ];
    }

    const TYPE_WEEKLY   = 'weekly';
    const TYPE_SPECIFIC = 'specific';

    // 曜日ラベル
    public static array $dayLabels = [
        0 => '日曜',
        1 => '月曜',
        2 => '火曜',
        3 => '水曜',
        4 => '木曜',
        5 => '金曜',
        6 => '土曜',
    ];

    public function getDayLabelAttribute(): string
    {
        if ($this->type === self::TYPE_WEEKLY) {
            return self::$dayLabels[$this->day_of_week] ?? '不明';
        }
        return $this->date?->format('Y/m/d') ?? '不明';
    }

    // ── スコープ ────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWeekly($query)
    {
        return $query->where('type', self::TYPE_WEEKLY);
    }

    public function scopeSpecific($query)
    {
        return $query->where('type', self::TYPE_SPECIFIC);
    }

    /**
     * 指定日が定休日かどうかを判定
     */
    public static function isClosedOn(Carbon $date): bool
    {
        $dayOfWeek = (int) $date->dayOfWeek;
        $dateStr   = $date->toDateString();

        return static::active()->where(function ($q) use ($dayOfWeek, $dateStr) {
            $q->where(function ($q) use ($dayOfWeek) {
                $q->where('type', self::TYPE_WEEKLY)
                  ->where('day_of_week', $dayOfWeek);
            })->orWhere(function ($q) use ($dateStr) {
                $q->where('type', self::TYPE_SPECIFIC)
                  ->where('date', $dateStr);
            });
        })->exists();
    }
}
