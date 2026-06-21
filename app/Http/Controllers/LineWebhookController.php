<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class LineWebhookController extends Controller
{
    public function __construct(private ReservationService $reservationService) {}

    /**
     * LINE Webhook 受信（将来拡張用）
     */
    public function handle(Request $request): Response
    {
        // 署名検証はミドルウェアで実装予定
        Log::info('LINE webhook received', $request->all());
        return response('OK', 200);
    }

    /**
     * 管理者がLINE通知からタップした承認・却下アクション
     * GET /webhook/line/action?action=approve&id=1&token=xxx
     */
    public function action(Request $request): \Illuminate\Http\RedirectResponse
    {
        // 署名付きURLの検証
        if (! URL::hasValidSignature($request)) {
            abort(403, '無効または期限切れのリンクです。');
        }

        $action      = $request->query('action');
        $reservation = Reservation::findOrFail($request->query('id'));
        $master      = \App\Models\User::masters()->first();

        match ($action) {
            'approve' => $this->reservationService->approve($reservation, $master),
            'reject'  => $this->reservationService->reject($reservation, $master),
            default   => abort(400),
        };

        $label = $action === 'approve' ? '承認' : '却下';
        return redirect('/')->with('success', "予約#{$reservation->id} を{$label}しました。");
    }
}
