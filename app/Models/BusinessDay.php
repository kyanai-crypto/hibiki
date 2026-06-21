<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessDay extends Model
{
    protected $fillable = [
        'date',
        'is_holiday',
        'morning_open',
        'afternoon_open',
        'night_open',
        'morning_capacity',
        'afternoon_capacity',
        'night_capacity',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'date'               => 'date',
            'is_holiday'         => 'boolean',
            'morning_open'       => 'boolean',
            'afternoon_open'     => 'boolean',
            'night_open'         => 'boolean',
            'morning_capacity'   => 'integer',
            'afternoon_capacity' => 'integer',
            'night_capacity'     => 'integer',
        ];
    }

    public function scopeHolidays($query)
    {
        return $query->where('is_holiday', true);
    }

    /**
     * 便ごとの定員上書き値を返す（nullの場合はsettingsの基本定員を使う）
     */
    public function getCapacityFor(string $tripType): ?int
    {
        return match ($tripType) {
            'morning'   => $this->morning_capacity,
            'afternoon' => $this->afternoon_capacity,
            'night'     => $this->night_capacity,
            default     => null,
        };
    }

    /**
     * 便が営業中かどうか
     */
    public function isOpenFor(string $tripType): bool
    {
        if ($this->is_holiday) {
            return false;
        }
        return match ($tripType) {
            'morning'   => $this->morning_open,
            'afternoon' => $this->afternoon_open,
            'night'     => $this->night_open,
            default     => false,
        };
    }
}
