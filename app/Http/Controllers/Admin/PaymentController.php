<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['student', 'paymentMethod', 'paymentRecipient', 'approvedBy', 'rejectedBy']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->purpose) {
            $query->where('purpose', $request->purpose);
        }

        if ($request->method) {
            $query->where('payment_method_id', $request->method);
        }

        if ($request->date_from) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->latest('payment_date')->paginate(20);

        return Inertia::render('Admin/Payments/Index', [
            'payments' => [
                'data' => $payments->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'student_name' => $payment->student->firstnames . ' ' . $payment->student->surname,
                        'student_email' => $payment->student->user->email ?? 'N/A',
                        'amount' => number_format($payment->amount, 2),
                        'final_amount' => number_format($payment->final_amount, 2),
                        'purpose' => $payment->purpose instanceof \BackedEnum ? $payment->purpose->value : $payment->purpose,
                        'status' => $payment->status instanceof \BackedEnum ? $payment->status->value : $payment->status,
                        'reference' => $payment->reference,
                        'description' => $payment->description,
                        'payment_date' => $payment->payment_date->format('Y-m-d'),
                        'created_at' => $payment->created_at->format('Y-m-d H:i'),
                        'payment_method' => [
                            'name' => $payment->paymentMethod->name ?? 'N/A',
                        ],
                        'payment_recipient' => $payment->paymentRecipient ? [
                            'name' => $payment->paymentRecipient->name,
                        ] : null,
                        'approved_by' => $payment->approvedBy ? $payment->approvedBy->name : null,
                        'approved_at' => $payment->approved_at ? $payment->approved_at->format('Y-m-d H:i') : null,
                        'rejected_by' => $payment->rejectedBy ? $payment->rejectedBy->name : null,
                        'rejected_at' => $payment->rejected_at ? $payment->rejected_at->format('Y-m-d H:i') : null,
                        'rejection_reason' => $payment->rejection_reason,
                    ];
                }),
                'links' => $payments->links(),
                'from' => $payments->firstItem(),
                'to' => $payments->lastItem(),
                'total' => $payments->total(),
            ],
            'filters' => $request->only(['status', 'purpose', 'method', 'date_from', 'date_to']),
        ]);
    }

    public function approve(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status !== PaymentStatus::PENDING) {
            return back()->with('error', 'Only pending payments can be approved.');
        }

        DB::beginTransaction();
        try {
            $payment->update([
                'status' => PaymentStatus::APPROVED,
                'approved_by' => $request->user()->id,
                'approved_at' => now(),
            ]);

            DB::commit();

            return back()->with('success', 'Payment approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve payment.');
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $payment = Payment::findOrFail($id);

        if ($payment->status !== PaymentStatus::PENDING) {
            return back()->with('error', 'Only pending payments can be rejected.');
        }

        DB::beginTransaction();
        try {
            $payment->update([
                'status' => PaymentStatus::REJECTED,
                'rejected_by' => $request->user()->id,
                'rejected_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            DB::commit();

            return back()->with('success', 'Payment rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject payment.');
        }
    }
}
