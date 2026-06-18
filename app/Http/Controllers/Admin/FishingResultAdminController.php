<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FishingResult;
use App\Models\FishingResultImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FishingResultAdminController extends Controller
{
    public function index(): View
    {
        $results = FishingResult::with('images')->latest('result_date')->paginate(15);
        return view('admin.fishing-results.index', compact('results'));
    }

    public function create(): View
    {
        return view('admin.fishing-results.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'result_date'  => ['required', 'date'],
            'fish_type'    => ['required', 'string', 'max:100'],
            'fish_size'    => ['nullable', 'string', 'max:100'],
            'comment'      => ['nullable', 'string', 'max:2000'],
            'is_published' => ['boolean'],
            'images'       => ['nullable', 'array', 'max:10'],
            'images.*'     => ['image', 'max:5120'],
        ]);

        $result = FishingResult::create(array_merge($validated, [
            'user_id' => Auth::id(),
        ]));

        $this->saveImages($result, $request);

        return redirect()->route('admin.fishing-results.index')->with('success', '釣果情報を登録しました。');
    }

    public function edit(FishingResult $fishingResult): View
    {
        $fishingResult->load('images');
        return view('admin.fishing-results.edit', compact('fishingResult'));
    }

    public function update(Request $request, FishingResult $fishingResult): RedirectResponse
    {
        $validated = $request->validate([
            'result_date'  => ['required', 'date'],
            'fish_type'    => ['required', 'string', 'max:100'],
            'fish_size'    => ['nullable', 'string', 'max:100'],
            'comment'      => ['nullable', 'string', 'max:2000'],
            'is_published' => ['boolean'],
            'images'       => ['nullable', 'array', 'max:10'],
            'images.*'     => ['image', 'max:5120'],
        ]);

        $fishingResult->update($validated);
        $this->saveImages($fishingResult, $request);

        return redirect()->route('admin.fishing-results.index')->with('success', '釣果情報を更新しました。');
    }

    public function destroy(FishingResult $fishingResult): RedirectResponse
    {
        foreach ($fishingResult->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        $fishingResult->delete();
        return redirect()->route('admin.fishing-results.index')->with('success', '釣果情報を削除しました。');
    }

    public function storeImage(Request $request, FishingResult $fishingResult): RedirectResponse
    {
        $request->validate(['images' => ['required', 'array'], 'images.*' => ['image', 'max:5120']]);
        $this->saveImages($fishingResult, $request);
        return back()->with('success', '写真を追加しました。');
    }

    public function destroyImage(FishingResultImage $image): RedirectResponse
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();
        return back()->with('success', '写真を削除しました。');
    }

    private function saveImages(FishingResult $result, Request $request): void
    {
        if (! $request->hasFile('images')) return;

        $order = $result->images()->max('sort_order') ?? 0;
        foreach ($request->file('images') as $file) {
            $path = $file->store('fishing-results', 'public');
            $result->images()->create(['path' => $path, 'sort_order' => ++$order]);
        }
    }
}
