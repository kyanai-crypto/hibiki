<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationCancelledByMasterNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Reservation $reservation) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $r = $this->reservation;
        return (new MailMessage)
            ->subject('【予約キャンセル】' . $r->reserved_date->format('m月d日') . ' ' . $r->tripTypeLabel)
            ->greeting($notifiable->name . ' 様')
            ->line('詢ねになりますが、运定側の都合により予約をキャンセルさせていただきました。')
            ->line('予約日：' . $r->reserved_date->format('Y年m月d日'))
            ->line('便：' . $r->tripTypeLabel)
            ->when($r->cancel_reason, fn($m) => $m->line('理由：' . $r->cancel_reason))
            ->line('大変々4申し訳ございません。またのご利用をお待ちしております。');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'type'           => 'cancelled_by_master',
        ];
    }
}
