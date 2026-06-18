<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Reservation $reservation): bool
    {
        return $user->isMaster() || $reservation->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isMember();
    }

    public function approve(User $user): bool
    {
        return $user->isMaster();
    }

    public function reject(User $user): bool
    {
        return $user->isMaster();
    }

    public function cancel(User $user, Reservation $reservation): bool
    {
        if (! $reservation->isCancellable()) {
            return false;
        }
        // 会員は自分の予約のみ、管理者は全件
        return $user->isMaster() || $reservation->user_id === $user->id;
    }

    public function complete(User $user): bool
    {
        return $user->isMaster();
    }
}
