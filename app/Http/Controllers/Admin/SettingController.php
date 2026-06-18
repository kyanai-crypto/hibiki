<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'default_capacity'       => ['required', 'integer', 'min:1', 'max:99'],
            'capacity_threshold_few' => ['required', 'integer', 'min:1', 'max:99'],
            'morning_open'           => ['boolean'],
            'afternoon_open'         => ['boolean'],
            'night_open'             => ['boolean'],
            'price_info'             => ['nullable', 'string'],
            'site_name'              => ['required', 'string', 'max:100'],
        ]);

        $keys = [
            'default_capacity', 'capacity_threshold_few',
            'morning_open', 'afternoon_open', 'night_open',
            'price_info', 'site_name',
        ];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key, '0'));
        }

        return back()->with('success', '設定を保存しました。');
    }
}
