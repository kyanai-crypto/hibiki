<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'role',
        'line_user_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── リレーション ───────────────────────────────

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function fishingResults(): HasMany
    {
        return $this->hasMany(FishingResult::class);
    }

    // ── アクセサ ────────────────────────────────────

    public function isMaster(): bool
    {
        return $this->role === 'master';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    // ── スコープ ────────────────────────────────────

    public function scopeMasters($query)
    {
        return $query->where('role', 'master');
    }

    public function scopeMembers($query)
    {
        return $query->where('role', 'member');
    }
}
