<?php

namespace App\Http\Controllers;

use App\Services\CalendarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function __construct(private CalendarService $calendarService) {}

    public function index(Request $request): View
    {
        $year  = (int) $request->query('year',  now()->year);
        $month = (int) $request->query('month', now()->month);

        $calendarData = $this->calendarService->getMonthData($year, $month);

        $prevMonth = now()->setDate($year, $month, 1)->subMonth();
        $nextMonth = now()->setDate($year, $month, 1)->addMonth();

        return view('public.calendar', compact('year', 'month', 'calendarData', 'prevMonth', 'nextMonth'));
    }

    public function data(Request $request): JsonResponse
    {
        $year  = (int) $request->query('year',  now()->year);
        $month = (int) $request->query('month', now()->month);

        return response()->json($this->calendarService->getMonthData($year, $month));
    }
}
