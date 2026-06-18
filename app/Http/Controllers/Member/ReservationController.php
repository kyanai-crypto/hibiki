<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\StoreReservationRequest;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function __construct(private ReservationService $reservationService) {}

    public function index(): View
    {
        $reservations = Auth::user()
            ->reservations()
            ->orderBy('reserved_date', 'desc')
            ->paginate(10);

        return view('member.reservations.index', compact('reservations'));
    }

    public function create(Request $request): View
    {
        $date     = $request->query('date');
        $tripType = $request->query('trip_type');

        return view('member.reservations.create', compact('date', 'tripType'));
    }

    public function store(StoreReservationRequest $request): RedirectResponse
    {
        $reservation = $this->reservationService->create(Auth::user(), $request->validated());

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', '予約申請を受け付けました。承認までしばらくお待ちください。');
    }

    public function show(Reservation $reservation): View
    {
        $this->authorize('view', $reservation);

        return view('member.reservations.show', compact('reservation'));
    }

    public function cancel(Reservation $reservation): RedirectResponse
    {
        $this->authorize('cancel', $reservation);

        $this->reservationService->cancelByMember($reservation, Auth::user());

        return redirect()
            ->route('reservations.index')
            ->with('success', '予約をキャンセルしました。');
    }
}
