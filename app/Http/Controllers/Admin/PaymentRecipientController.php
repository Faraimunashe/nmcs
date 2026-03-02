<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentRecipient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentRecipientController extends Controller
{
    public function index()
    {
        $recipients = PaymentRecipient::orderBy('name')
            ->get(['id', 'name', 'details', 'is_active']);

        return Inertia::render('Admin/Settings/PaymentRecipients/Index', [
            'paymentRecipients' => $recipients,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ]);

        PaymentRecipient::create($validated);

        return redirect()
            ->route('admin.settings.payment-recipients.index')
            ->with('success', 'Payment recipient saved successfully.');
    }

    public function destroy(PaymentRecipient $paymentRecipient)
    {
        $paymentRecipient->delete();

        return redirect()
            ->route('admin.settings.payment-recipients.index')
            ->with('success', 'Payment recipient deleted.');
    }
}

