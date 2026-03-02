<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::orderBy('status')
            ->get(['id', 'status', 'description']);

        return Inertia::render('Admin/Settings/Memberships/Index', [
            'memberships' => $memberships,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Membership::create($validated);

        return redirect()
            ->route('admin.settings.memberships.index')
            ->with('success', 'Membership saved successfully.');
    }

    public function destroy(Membership $membership)
    {
        $membership->delete();

        return redirect()
            ->route('admin.settings.memberships.index')
            ->with('success', 'Membership deleted.');
    }
}

