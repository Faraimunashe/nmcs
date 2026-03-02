<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChronicCondition;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChronicConditionController extends Controller
{
    public function index()
    {
        $conditions = ChronicCondition::orderBy('name')
            ->get(['id', 'name', 'description']);

        return Inertia::render('Admin/Settings/ChronicConditions/Index', [
            'chronicConditions' => $conditions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        ChronicCondition::create($validated);

        return redirect()
            ->route('admin.settings.chronic-conditions.index')
            ->with('success', 'Chronic condition saved successfully.');
    }

    public function destroy(ChronicCondition $chronicCondition)
    {
        $chronicCondition->delete();

        return redirect()
            ->route('admin.settings.chronic-conditions.index')
            ->with('success', 'Chronic condition deleted.');
    }
}

