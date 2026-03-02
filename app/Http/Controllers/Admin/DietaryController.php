<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dietary;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DietaryController extends Controller
{
    public function index()
    {
        $dietaries = Dietary::orderBy('name')
            ->get(['id', 'name', 'description']);

        return Inertia::render('Admin/Settings/Dietaries/Index', [
            'dietaries' => $dietaries,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Dietary::create($validated);

        return redirect()
            ->route('admin.settings.dietaries.index')
            ->with('success', 'Dietary option saved successfully.');
    }

    public function destroy(Dietary $dietary)
    {
        $dietary->delete();

        return redirect()
            ->route('admin.settings.dietaries.index')
            ->with('success', 'Dietary option deleted.');
    }
}

