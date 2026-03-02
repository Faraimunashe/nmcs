<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConferenceFee;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConferenceFeeController extends Controller
{
    public function index()
    {
        $fees = ConferenceFee::orderByDesc('is_active')
            ->orderByDesc('created_at')
            ->get(['id', 'name', 'description', 'amount', 'is_active']);

        return Inertia::render('Admin/Settings/ConferenceFees/Index', [
            'conferenceFees' => $fees,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'amount' => ['required', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        if (!empty($validated['is_active'])) {
            ConferenceFee::where('is_active', true)->update(['is_active' => false]);
        }

        ConferenceFee::create($validated);

        return redirect()
            ->route('admin.settings.conference-fees.index')
            ->with('success', 'Conference fee saved successfully.');
    }

    public function destroy(ConferenceFee $conferenceFee)
    {
        $conferenceFee->delete();

        return redirect()
            ->route('admin.settings.conference-fees.index')
            ->with('success', 'Conference fee deleted.');
    }
}

