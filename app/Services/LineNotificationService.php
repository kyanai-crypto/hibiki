<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LineNotificationService
{
    private string $apiBase = 'https://api.line.me/v2/bot';

    private function token(): string
    {
        return Setting::get('line_channel_access_token', config('services.line.channel_access_token', ''));
    }

    private function adminUserId(): string
    {
        return Setting::get('line_admin_user_id', config('services.line.admin_user_id', ''));
    }

    /**
     * 管理者へ新規予約通知（承認・却下ボタン付き）
     */
    public function notifyNewReservation(Reservation $reservation): void
    {
        $adminId = $this->adminUserId();
        if (empty($adminId)) {
            Log::warning('LINE admin_user_id not configured.');
            return;
        }

        $tripLabel = match ($reservation->trip_type) {
            'morning'   => '午前便',
            'afternoon' => '午後便',
            'night'     => '夜便',
        };

        $approveUrl = route('webhook.line.action', ['action' => 'approve', 'id' => $reservation->id]);
        $rejectUrl  = route('webhook.line.action', ['action' => 'reject',  'id' => $reservation->id]);

        $message = [
            'type' => 'flex',
            'altText' => '新しい予約申請があります',
            'contents' => [
                'type' => 'bubble',
                'header' => [
                    'type'   => 'box',
                    'layout' => 'vertical',
                    'contents' => [
                        ['type' => 'text', 'text' => '🚤 新規予約申請', 'weight' => 'bold', 'size' => 'lg', 'color' => '#ffffff'],
                    ],
                    'backgroundColor' => '#0d6efd',
                    'paddingAll' => '16px',
                ],
                'body' => [
                    'type'   => 'box',
                    'layout' => 'vertical',
                    'spacing' => 'md',
                    'contents' => [
                        $this->flexRow('氏名',    $reservation->user->name),
                        $this->flexRow('予約日',  $reservation->reserved_date->format('Y年m月d日')),
                        $this->flexRow('便',      $tripLabel),
                        $this->flexRow('人数',    $reservation->num_people . '名'),
                        $this->flexRow('備考',    $reservation->remarks ?? 'なし'),
                    ],
                ],
                'footer' => [
                    'type'   => 'box',
                    'layout' => 'vertical',
                    'spacing' => 'sm',
                    'contents' => [
                        [
                            'type'   => 'button',
                            'style'  => 'primary',
                            'color'  => '#198754',
                            'action' => ['type' => 'uri', 'label' => '✅ 承認', 'uri' => $approveUrl],
                        ],
                        [
                            'type'   => 'button',
                            'style'  => 'primary',
                            'color'  => '#dc3545',
                            'action' => ['type' => 'uri', 'label' => '❌ 却下', 'uri' => $rejectUrl],
                        ],
                    ],
                ],
            ],
        ];

        $this->pushMessage($adminId, $message);
    }

    /**
     * 将来拡張用: 任b意のテキストを管理者へ送信
     */
    public function notifyAdmin(string $text): void
    {
        $adminId = $this->adminUserId();
        if (empty($adminId)) return;

        $this->pushMessage($adminId, ['type' => 'text', 'text' => $text]);
    }

    /**
     * 会員へ送信（LINE User IDが登録されている場合）
     */
    public function notifyUser(string $lineUserId, string $text): void
    {
        $this->pushMessage($lineUserId, ['type' => 'text', 'text' => $text]);
    }

    private function pushMessage(string $to, array $message): void
    {
        try {
            Http::withToken($this->token())
                ->post("{$this->apiBase}/message/push", [
                    'to'       => $to,
                    'messages' => [$message],
                ]);
        } catch (\Throwable $e) {
            Log::error('LINE push error: ' . $e->getMessage());
        }
    }

    private function flexRow(string $label, string $value): array
    {
        return [
            'type'   => 'box',
            'layout' => 'horizontal',
            'contents' => [
                ['type' => 'text', 'text' => $label, 'size' => 'sm', 'color' => '#888888', 'flex' => 2],
                ['type' => 'text', 'text' => $value, 'size' => 'sm', 'wrap' => true, 'flex' => 4],
            ],
        ];
    }
}
