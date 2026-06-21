<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationApprovedNotification extends Notification implements ShouldQueue
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
            ->subject('【予約承認】' . $r->reserved_date->format('m月d日') . ' ' . $r->tripTypeLabel)
            ->greeting($notifiable->name . ' 様')
            ->line('予約が承認されました。')
            ->line('予約日：' . $r->reserved_date->format('Y年m月d日'))
            ->line('便：' . $r->tripTypeLabel)
            ->line('人数：' . $r->num_people . '名')
            ->action('予約詳細を確認', route('reservations.show', $r))
            ->line('当日はお気をつけてお越しください。');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'type'           => 'approved',
        ];
    }
}
