<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'pending'  => Reservation::pending()->count(),
            'approved' => Reservation::approved()->whereBetween('reserved_date', [today(), today()->addDays(7)])->count(),
            'members'  => User::members()->count(),
            'today'    => Reservation::approved()->forDate(today())->count(),
        ];

        $pendingReservations = Reservation::with('user')
            ->pending()
            ->orderBy('created_at')
            ->limit(10)
            ->get();

        $upcomingReservations = Reservation::with('user')
            ->approved()
            ->where('reserved_date', '>=', today())
            ->orderBy('reserved_date')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingReservations', 'upcomingReservations'));
    }
}
