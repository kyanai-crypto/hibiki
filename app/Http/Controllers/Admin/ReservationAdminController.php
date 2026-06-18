<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReservationAdminController extends Controller
{
    public function __construct(private ReservationService $reservationService) {}

    public function index(Request $request): View
    {
        $query = Reservation::with('user')->orderBy('reserved_date', 'desc');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }
        if ($date = $request->query('date')) {
            $query->where('reserved_date', $date);
        }

        $reservations = $query->paginate(20)->withQueryString();

        return view('admin.reservations.index', compact('reservations'));
    }

    public function show(Reservation $reservation): View
    {
        $reservation->load('user', 'approver');
        return view('admin.reservations.show', compact('reservation'));
    }

    public function approve(Reservation $reservation): RedirectResponse
    {
        $this->authorize('approve', $reservation);
        $this->reservationService->approve($reservation, Auth::user());
        return back()->with('success', '予約を承認しました。');
    }

    public function reject(Request $request, Reservation $reservation): RedirectResponse
    {
        $this->authorize('reject', $reservation);
        $request->validate(['reject_reason' => ['nullable', 'string', 'max:500']]);
        $this->reservationService->reject($reservation, Auth::user(), $request->input('reject_reason', ''));
        return back()->with('success', '予約を却下しました。');
    }

    public function cancel(Request $request, Reservation $reservation): RedirectResponse
    {
        $this->authorize('cancel', $reservation);
        $request->validate(['cancel_reason' => ['required', 'string', 'max:500']]);
        $this->reservationService->cancelByMaster($reservation, Auth::user(), $request->cancel_reason);
        return back()->with('success', '予約をキャンセルしました。');
    }

    public function complete(Reservation $reservation): RedirectResponse
    {
        $this->authorize('complete', $reservation);
        $this->reservationService->complete($reservation);
        return back()->with('success', '出船完了にしました。');
    }
}
