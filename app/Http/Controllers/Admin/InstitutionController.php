<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class InstitutionController extends Controller
{
    public function index()
    {
        $institutions = Institution::with('region')
            ->orderBy('name')
            ->get(['id', 'region_id', 'code', 'name', 'logo']);

        $regions = Region::orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('Admin/Settings/Institutions/Index', [
            'institutions' => $institutions,
            'regions' => $regions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'region_id' => ['required', Rule::exists('regions', 'id')],
            'code' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('institutions', 'public');
            $validated['logo'] = $path;
        }

        Institution::create($validated);

        return redirect()
            ->route('admin.settings.institutions.index')
            ->with('success', 'Institution saved successfully.');
    }

    public function update(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'region_id' => ['required', Rule::exists('regions', 'id')],
            'code' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('institutions', 'public');
            $validated['logo'] = $path;
        }

        $institution->update($validated);

        return redirect()
            ->route('admin.settings.institutions.index')
            ->with('success', 'Institution updated successfully.');
    }

    public function destroy(Institution $institution)
    {
        $institution->delete();

        return redirect()
            ->route('admin.settings.institutions.index')
            ->with('success', 'Institution deleted.');
    }
}

