<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Events\ConferenceFeesFullyPaid;
use App\Events\PaymentApproved;
use App\Events\PaymentRejected;
use App\Http\Controllers\Controller;
use App\Models\ConferenceFee;
use App\Models\Payment;
use App\Services\TwilioSmsService;
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

        $method = $request->get('method');
        if (! empty($method)) {
            $query->where('payment_method_id', $method);
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
                        'student_name' => $payment->student->firstnames.' '.$payment->student->surname,
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
                // Pagination links as JSON-safe array (url/label/active)
                'links' => $payments->linkCollection()->toArray(),
                'from' => $payments->firstItem(),
                'to' => $payments->lastItem(),
                'total' => $payments->total(),
            ],
            'filters' => $request->only(['status', 'purpose', 'method', 'date_from', 'date_to']),
        ]);
    }

    public function show(Request $request, $id)
    {
        $payment = Payment::with([
            'student.user',
            'student.membership',
            'student.institution.region',
            'paymentMethod',
            'paymentRecipient',
            'approvedBy',
            'rejectedBy',
        ])->findOrFail($id);

        return Inertia::render('Admin/Payments/Show', [
            'payment' => [
                'id' => $payment->id,
                'amount' => number_format($payment->amount, 2),
                'final_amount' => number_format($payment->final_amount, 2),
                'purpose' => $payment->purpose instanceof \BackedEnum ? $payment->purpose->value : $payment->purpose,
                'status' => $payment->status instanceof \BackedEnum ? $payment->status->value : $payment->status,
                'reference' => $payment->reference,
                'description' => $payment->description,
                'payment_date' => $payment->payment_date->format('Y-m-d'),
                'created_at' => $payment->created_at->format('Y-m-d H:i'),
                'student' => [
                    'name' => $payment->student->firstnames.' '.$payment->student->surname,
                    'email' => $payment->student->user->email ?? 'N/A',
                    'institution' => $payment->student->institution ? [
                        'name' => $payment->student->institution->name,
                        'code' => $payment->student->institution->code,
                    ] : null,
                    'region' => $payment->student->institution?->region ? [
                        'name' => $payment->student->institution->region->name,
                    ] : null,
                    'membership' => $payment->student->membership ? [
                        'status' => $payment->student->membership->status instanceof \BackedEnum
                            ? $payment->student->membership->status->value
                            : ($payment->student->membership->status ?? null),
                        'description' => $payment->student->membership->description,
                    ] : null,
                ],
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
            ],
        ]);
    }

    public function approve(Request $request, $id)
    {
        $payment = Payment::with(['student.phones'])->findOrFail($id);

        if ($payment->status !== PaymentStatus::PENDING) {
            return back()->with('error', 'Only pending payments can be approved.');
        }

        $student = $payment->student;
        $totalPaidBefore = Payment::where('student_id', $student->id)
            ->where('status', PaymentStatus::APPROVED)
            ->sum('final_amount');

        DB::beginTransaction();
        try {
            $payment->update([
                'status' => PaymentStatus::APPROVED,
                'approved_by' => $request->user()->id,
                'approved_at' => now(),
            ]);

            DB::commit();

            $payment->load(['student.user', 'paymentMethod']);
            event(new PaymentApproved($payment));

            // Notify user via SMS about approval
            $phone = optional($student->phones()->first())->phone;
            if ($phone) {
                $appUrl = config('app.url');
                app(TwilioSmsService::class)->send(
                    $phone,
                    sprintf(
                        'Hi %s, your payment of %s for the NMCS conference has been APPROVED. Thank you! %s',
                        trim($student->firstnames.' '.$student->surname),
                        number_format($payment->final_amount, 2),
                        $appUrl ? 'More details: '.$appUrl : ''
                    )
                );
            }

            $activeFee = ConferenceFee::getActiveFee();
            if ($activeFee && (float) $activeFee->amount > 0) {
                $totalPaidAfter = Payment::where('student_id', $student->id)
                    ->where('status', PaymentStatus::APPROVED)
                    ->sum('final_amount');
                $required = (float) $activeFee->amount;
                if ((float) $totalPaidAfter >= $required && (float) $totalPaidBefore < $required) {
                    event(new ConferenceFeesFullyPaid(
                        $student->load('user'),
                        number_format((float) $totalPaidAfter, 2),
                        number_format($required, 2),
                    ));
                }
            }

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

        $payment = Payment::with(['student.phones'])->findOrFail($id);

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

            $payment->load(['student.user', 'paymentMethod']);
            event(new PaymentRejected($payment));

            // Notify user via SMS about rejection
            $student = $payment->student;
            $phone = optional($student->phones()->first())->phone;
            if ($phone) {
                $appUrl = config('app.url');
                app(TwilioSmsService::class)->send(
                    $phone,
                    sprintf(
                        'Hi %s, your payment of %s for the NMCS conference has been REJECTED. Reason: %s %s',
                        trim($student->firstnames.' '.$student->surname),
                        number_format($payment->final_amount, 2),
                        $payment->rejection_reason,
                        $appUrl ? 'More details: '.$appUrl : ''
                    )
                );
            }

            return back()->with('success', 'Payment rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to reject payment.');
        }
    }
}
