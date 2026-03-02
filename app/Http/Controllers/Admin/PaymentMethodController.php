<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentCharge;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::with('paymentCharge')
            ->orderBy('name')
            ->get(['id', 'payment_charge_id', 'name', 'requires_reference', 'is_active']);

        $charges = PaymentCharge::orderBy('narration')
            ->get(['id', 'narration', 'amount']);

        return Inertia::render('Admin/Settings/PaymentMethods/Index', [
            'paymentMethods' => $methods,
            'paymentCharges' => $charges,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'payment_charge_id' => ['nullable', Rule::exists('payment_charges', 'id')],
            'requires_reference' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        PaymentMethod::create($validated);

        return redirect()
            ->route('admin.settings.payment-methods.index')
            ->with('success', 'Payment method saved successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return redirect()
            ->route('admin.settings.payment-methods.index')
            ->with('success', 'Payment method deleted.');
    }
}

