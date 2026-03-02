<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('Admin/Settings/Regions/Index', [
            'regions' => $regions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Region::create($validated);

        return redirect()
            ->route('admin.settings.regions.index')
            ->with('success', 'Region saved successfully.');
    }

    public function destroy(Region $region)
    {
        $region->delete();

        return redirect()
            ->route('admin.settings.regions.index')
            ->with('success', 'Region deleted.');
    }
}

