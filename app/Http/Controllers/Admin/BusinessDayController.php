<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessDay;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessDayController extends Controller
{
    public function index(): View
    {
        $businessDays = BusinessDay::orderBy('date', 'desc')->paginate(20);
        return view('admin.business-days.index', compact('businessDays'));
    }

    public function create(): View
    {
        return view('admin.business-days.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date'               => ['required', 'date', 'unique:business_days,date'],
            'is_holiday'         => ['boolean'],
            'morning_open'       => ['boolean'],
            'afternoon_open'     => ['boolean'],
            'night_open'         => ['boolean'],
            'morning_capacity'   => ['nullable', 'integer', 'min:1', 'max:99'],
            'afternoon_capacity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'night_capacity'     => ['nullable', 'integer', 'min:1', 'max:99'],
            'note'               => ['nullable', 'string', 'max:255'],
        ]);

        BusinessDay::create($validated);
        return redirect()->route('admin.business-days.index')->with('success', '営業日設定を追加しました。');
    }

    public function edit(BusinessDay $businessDay): View
    {
        return view('admin.business-days.edit', compact('businessDay'));
    }

    public function update(Request $request, BusinessDay $businessDay): RedirectResponse
    {
        $validated = $request->validate([
            'is_holiday'         => ['boolean'],
            'morning_open'       => ['boolean'],
            'afternoon_open'     => ['boolean'],
            'night_open'         => ['boolean'],
            'morning_capacity'   => ['nullable', 'integer', 'min:1', 'max:99'],
            'afternoon_capacity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'night_capacity'     => ['nullable', 'integer', 'min:1', 'max:99'],
            'note'               => ['nullable', 'string', 'max:255'],
        ]);

        $businessDay->update($validated);
        return redirect()->route('admin.business-days.index')->with('success', '営業日設定を更新しました。');
    }

    public function destroy(BusinessDay $businessDay): RedirectResponse
    {
        $businessDay->delete();
        return redirect()->route('admin.business-days.index')->with('success', '営業日設定を削除しました。');
    }
}
