<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Disability;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DisabilityController extends Controller
{
    public function index()
    {
        $disabilities = Disability::orderBy('name')
            ->get(['id', 'name', 'description']);

        return Inertia::render('Admin/Settings/Disabilities/Index', [
            'disabilities' => $disabilities,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Disability::create($validated);

        return redirect()
            ->route('admin.settings.disabilities.index')
            ->with('success', 'Disability saved successfully.');
    }

    public function destroy(Disability $disability)
    {
        $disability->delete();

        return redirect()
            ->route('admin.settings.disabilities.index')
            ->with('success', 'Disability deleted.');
    }
}

