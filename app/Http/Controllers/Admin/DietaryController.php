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
            ->paginate(20, ['id', 'name', 'description'])
            ->withQueryString();

        return Inertia::render('Admin/Settings/Dietaries/Index', [
            'dietaries' => [
                'data' => $dietaries->items(),
                // Pagination links as JSON-safe array (url/label/active)
                'links' => $dietaries->linkCollection()->toArray(),
                'from' => $dietaries->firstItem(),
                'to' => $dietaries->lastItem(),
                'total' => $dietaries->total(),
            ],
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

