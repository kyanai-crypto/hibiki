<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationRejectedNotification extends Notification implements ShouldQueue
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
            ->subject('【予約却下】' . $r->reserved_date->format('m月d日') . ' ' . $r->tripTypeLabel)
            ->greeting($notifiable->name . ' 様')
            ->line('詢ねになりますが、予約をお断りさせていただきました。')
            ->line('予約日：' . $r->reserved_date->format('Y年m月d日'))
            ->line('便：' . $r->tripTypeLabel)
            ->when($r->reject_reason, fn($m) => $m->line('理由：' . $r->reject_reason))
            ->line('またのご利用をお待ちしております。');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'type'           => 'rejected',
        ];
    }
}
