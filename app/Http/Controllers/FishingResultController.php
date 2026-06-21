<?php

namespace App\Http\Controllers;

use App\Models\FishingResult;
use Illuminate\View\View;

class FishingResultController extends Controller
{
    public function index(): View
    {
        $results = FishingResult::with('images')
            ->published()
            ->latest()
            ->paginate(12);

        return view('public.fishing-results.index', compact('results'));
    }

    public function show(FishingResult $fishingResult): View
    {
        abort_unless($fishingResult->is_published, 404);
        $fishingResult->load('images');
        return view('public.fishing-results.show', compact('fishingResult'));
    }
}
