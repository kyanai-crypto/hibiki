<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClosedDay;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClosedDayController extends Controller
{
    public function index(): View
    {
        $weeklyDays  = ClosedDay::weekly()->active()->orderBy('day_of_week')->get();
        $specificDays = ClosedDay::specific()->active()->orderBy('date')->get();
        return view('admin.closed-days.index', compact('weeklyDays', 'specificDays'));
    }

    public function create(): View
    {
        return view('admin.closed-days.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type'        => ['required', Rule::in(['weekly', 'specific'])],
            'day_of_week' => ['required_if:type,weekly', 'nullable', 'integer', 'between:0,6'],
            'date'        => ['required_if:type,specific', 'nullable', 'date'],
            'reason'      => ['nullable', 'string', 'max:255'],
            'is_active'   => ['boolean'],
        ]);

        ClosedDay::create($validated);
        return redirect()->route('admin.closed-days.index')->with('success', '定休日を追加しました。');
    }

    public function edit(ClosedDay $closedDay): View
    {
        return view('admin.closed-days.edit', compact('closedDay'));
    }

    public function update(Request $request, ClosedDay $closedDay): RedirectResponse
    {
        $validated = $request->validate([
            'reason'    => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $closedDay->update($validated);
        return redirect()->route('admin.closed-days.index')->with('success', '定休日設定を更新しました。');
    }

    public function destroy(ClosedDay $closedDay): RedirectResponse
    {
        $closedDay->delete();
        return redirect()->route('admin.closed-days.index')->with('success', '定休日を削除しました。');
    }
}
