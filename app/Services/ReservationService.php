<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ReservationApprovedNotification;
use App\Notifications\ReservationCancelledByMasterNotification;
use App\Notifications\ReservationRejectedNotification;
use App\Services\LineNotificationService;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    public function __construct(private LineNotificationService $line) {}

    /**
     * 予約作成（会員から申請）
     */
    public function create(User $user, array $data): Reservation
    {
        return DB::transaction(function () use ($user, $data) {
            $reservation = $user->reservations()->create([
                'reserved_date' => $data['reserved_date'],
                'trip_type'     => $data['trip_type'],
                'num_people'    => $data['num_people'],
                'remarks'       => $data['remarks'] ?? null,
                'status'        => Reservation::STATUS_PENDING,
            ]);

            // LINE管理者通知
            $this->line->notifyNewReservation($reservation);

            return $reservation;
        });
    }

    /**
     * 承認（管理者から）
     */
    public function approve(Reservation $reservation, User $approver): void
    {
        DB::transaction(function () use ($reservation, $approver) {
            $reservation->update([
                'status'      => Reservation::STATUS_APPROVED,
                'approved_by' => $approver->id,
                'approved_at' => now(),
            ]);

            $reservation->user->notify(new ReservationApprovedNotification($reservation));
        });
    }

    /**
     * 却下（管理者から）
     */
    public function reject(Reservation $reservation, User $approver, string $reason = ''): void
    {
        DB::transaction(function () use ($reservation, $approver, $reason) {
            $reservation->update([
                'status'      => Reservation::STATUS_REJECTED,
                'approved_by' => $approver->id,
                'approved_at' => now(),
                'reject_reason' => $reason,
            ]);

            $reservation->user->notify(new ReservationRejectedNotification($reservation));
        });
    }

    /**
     * キャンセル（会員から）
     */
    public function cancelByMember(Reservation $reservation, User $user): void
    {
        $reservation->update([
            'status'       => Reservation::STATUS_CANCELLED,
            'cancelled_by' => 'member',
            'cancelled_at' => now(),
        ]);
    }

    /**
     * キャンセル（管理者から）
     */
    public function cancelByMaster(Reservation $reservation, User $master, string $reason): void
    {
        DB::transaction(function () use ($reservation, $master, $reason) {
            $reservation->update([
                'status'        => Reservation::STATUS_CANCELLED,
                'cancelled_by'  => 'master',
                'cancel_reason' => $reason,
                'cancelled_at'  => now(),
            ]);

            $reservation->user->notify(new ReservationCancelledByMasterNotification($reservation));
        });
    }

    /**
     * 出船完了
     */
    public function complete(Reservation $reservation): void
    {
        $reservation->update(['status' => Reservation::STATUS_COMPLETED]);
    }
}
