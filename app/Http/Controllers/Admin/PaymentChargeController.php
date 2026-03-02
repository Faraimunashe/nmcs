<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentCharge;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentChargeController extends Controller
{
    public function index()
    {
        $charges = PaymentCharge::orderBy('narration')
            ->get(['id', 'narration', 'amount']);

        return Inertia::render('Admin/Settings/PaymentCharges/Index', [
            'paymentCharges' => $charges,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'narration' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        PaymentCharge::create($validated);

        return redirect()
            ->route('admin.settings.payment-charges.index')
            ->with('success', 'Payment charge saved successfully.');
    }

    public function destroy(PaymentCharge $paymentCharge)
    {
        $paymentCharge->delete();

        return redirect()
            ->route('admin.settings.payment-charges.index')
            ->with('success', 'Payment charge deleted.');
    }
}

