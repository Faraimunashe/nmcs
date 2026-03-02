<?php

namespace App\Http\Controllers;

use App\Enums\PaymentPurpose;
use App\Enums\PaymentStatus;
use App\Models\ConferenceFee;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentRecipient;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Payment::whereHas('student', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with(['student', 'paymentMethod', 'paymentRecipient']);

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

        $payments = $query->latest('payment_date')->paginate(15);

        $student = Student::where('user_id', $user->id)->first();
        
        $totalPaid = Payment::where('student_id', $student->id)
            ->where('status', PaymentStatus::APPROVED)
            ->sum('final_amount');
        
        $totalPending = Payment::where('student_id', $student->id)
            ->where('status', PaymentStatus::PENDING)
            ->sum('final_amount');
        
        $conferenceFee = ConferenceFee::getActiveFee();
        $conferenceFeeAmount = $conferenceFee ? $conferenceFee->amount : 0;
        $balance = $conferenceFeeAmount - $totalPaid;

        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return Inertia::render('Payments/Index', [
            'payments' => [
                'data' => $payments->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'amount' => number_format($payment->amount, 2),
                        'final_amount' => number_format($payment->final_amount, 2),
                        'purpose' => $payment->purpose instanceof \BackedEnum ? $payment->purpose->value : $payment->purpose,
                        'status' => $payment->status instanceof \BackedEnum ? $payment->status->value : $payment->status,
                        'reference' => $payment->reference,
                        'description' => $payment->description,
                        'payment_date' => $payment->payment_date->format('Y-m-d'),
                        'created_at' => $payment->created_at->format('Y-m-d H:i'),
                        'payment_method' => [
                            'id' => $payment->paymentMethod->id,
                            'name' => $payment->paymentMethod->name,
                        ],
                        'payment_recipient' => $payment->paymentRecipient ? [
                            'name' => $payment->paymentRecipient->name,
                        ] : null,
                        'student' => [
                            'firstnames' => $payment->student->firstnames,
                            'surname' => $payment->student->surname,
                        ],
                        'rejection_reason' => $payment->rejection_reason,
                    ];
                }),
                'links' => $payments->links(),
                'from' => $payments->firstItem(),
                'to' => $payments->lastItem(),
                'total' => $payments->total(),
            ],
            'paymentMethods' => $paymentMethods->map(function ($method) {
                return [
                    'id' => $method->id,
                    'name' => $method->name,
                ];
            }),
            'filters' => $request->only(['status', 'purpose', 'method', 'date_from', 'date_to']),
            'balanceSummary' => [
                'total_paid' => number_format($totalPaid, 2),
                'total_pending' => number_format($totalPending, 2),
                'balance' => number_format($balance, 2),
                'conference_fee' => number_format($conferenceFeeAmount, 2),
            ],
        ]);
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect('/attendants/create');
        }

        $paymentMethods = PaymentMethod::where('is_active', true)->with('paymentCharge')->get();

        return Inertia::render('Payments/Create', [
            'students' => [[
                'id' => $student->id,
                'firstnames' => $student->firstnames,
                'surname' => $student->surname,
            ]],
            'paymentMethods' => $paymentMethods->map(function ($method) {
                return [
                    'id' => $method->id,
                    'name' => $method->name,
                    'requires_reference' => $method->requires_reference,
                    'payment_charge' => $method->paymentCharge ? [
                        'amount' => $method->paymentCharge->amount,
                        'narration' => $method->paymentCharge->narration,
                    ] : null,
                ];
            }),
            'studentId' => $student->id,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'payment_recipient_name' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'purpose' => ['required', 'in:DEPOSIT,FULL_PAYMENT,BALANCE'],
            'reference' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'payment_date' => ['required', 'date'],
        ]);

        $student = Student::where('user_id', $user->id)->findOrFail($validated['student_id']);
        $paymentMethod = PaymentMethod::with('paymentCharge')->findOrFail($validated['payment_method_id']);

        if ($paymentMethod->requires_reference && empty($validated['reference'])) {
            return back()->withErrors(['reference' => 'Reference is required for this payment method.']);
        }

        $charge = $paymentMethod->paymentCharge->amount ?? 0;
        $finalAmount = max(0, $validated['amount'] - $charge);

        DB::beginTransaction();
        try {
            $paymentRecipientId = null;
            if (!empty($validated['payment_recipient_name'])) {
                $paymentRecipient = PaymentRecipient::firstOrCreate(
                    ['name' => trim($validated['payment_recipient_name'])],
                    ['is_active' => true]
                );
                $paymentRecipientId = $paymentRecipient->id;
            }

            $payment = Payment::create([
                'student_id' => $validated['student_id'],
                'payment_method_id' => $validated['payment_method_id'],
                'payment_recipient_id' => $paymentRecipientId,
                'amount' => $validated['amount'],
                'final_amount' => $finalAmount,
                'purpose' => PaymentPurpose::from($validated['purpose']),
                'reference' => $validated['reference'] ?? null,
                'description' => $validated['description'] ?? null,
                'payment_date' => $validated['payment_date'],
                'status' => PaymentStatus::PENDING,
            ]);

            DB::commit();

            return redirect('/payments')
                ->with('success', 'Payment recorded successfully. Awaiting approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to record payment. Please try again.']);
        }
    }

    public function edit(Request $request, $id)
    {
        $user = $request->user();
        $payment = Payment::whereHas('student', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with(['student', 'paymentMethod.paymentCharge', 'paymentRecipient'])
            ->findOrFail($id);

        if ($payment->status !== PaymentStatus::PENDING) {
            return redirect('/payments')
                ->with('error', 'Only pending payments can be edited.');
        }

        $student = Student::where('user_id', $user->id)->first();
        $paymentMethods = PaymentMethod::where('is_active', true)->with('paymentCharge')->get();

        return Inertia::render('Payments/Edit', [
            'payment' => [
                'id' => $payment->id,
                'student_id' => $payment->student_id,
                'payment_method_id' => $payment->payment_method_id,
                'payment_recipient_name' => $payment->paymentRecipient->name ?? '',
                'amount' => $payment->amount,
                'purpose' => $payment->purpose instanceof \BackedEnum ? $payment->purpose->value : $payment->purpose,
                'reference' => $payment->reference,
                'description' => $payment->description,
                'payment_date' => $payment->payment_date->format('Y-m-d'),
                'status' => $payment->status instanceof \BackedEnum ? $payment->status->value : $payment->status,
            ],
            'students' => [[
                'id' => $student->id,
                'firstnames' => $student->firstnames,
                'surname' => $student->surname,
            ]],
            'paymentMethods' => $paymentMethods->map(function ($method) {
                return [
                    'id' => $method->id,
                    'name' => $method->name,
                    'requires_reference' => $method->requires_reference,
                    'payment_charge' => $method->paymentCharge ? [
                        'amount' => $method->paymentCharge->amount,
                        'narration' => $method->paymentCharge->narration,
                    ] : null,
                ];
            }),
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $payment = Payment::whereHas('student', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($id);

        if ($payment->status !== PaymentStatus::PENDING) {
            return back()->withErrors(['error' => 'Only pending payments can be edited.']);
        }

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'payment_recipient_name' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'purpose' => ['required', 'in:DEPOSIT,FULL_PAYMENT,BALANCE'],
            'reference' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'payment_date' => ['required', 'date'],
        ]);

        $student = Student::where('user_id', $user->id)->findOrFail($validated['student_id']);
        $paymentMethod = PaymentMethod::with('paymentCharge')->findOrFail($validated['payment_method_id']);

        if ($paymentMethod->requires_reference && empty($validated['reference'])) {
            return back()->withErrors(['reference' => 'Reference is required for this payment method.']);
        }

        $charge = $paymentMethod->paymentCharge->amount ?? 0;
        $finalAmount = max(0, $validated['amount'] - $charge);

        DB::beginTransaction();
        try {
            $paymentRecipientId = null;
            if (!empty($validated['payment_recipient_name'])) {
                $paymentRecipient = PaymentRecipient::firstOrCreate(
                    ['name' => trim($validated['payment_recipient_name'])],
                    ['is_active' => true]
                );
                $paymentRecipientId = $paymentRecipient->id;
            }

            $payment->update([
                'student_id' => $validated['student_id'],
                'payment_method_id' => $validated['payment_method_id'],
                'payment_recipient_id' => $paymentRecipientId,
                'amount' => $validated['amount'],
                'final_amount' => $finalAmount,
                'purpose' => PaymentPurpose::from($validated['purpose']),
                'reference' => $validated['reference'] ?? null,
                'description' => $validated['description'] ?? null,
                'payment_date' => $validated['payment_date'],
            ]);

            DB::commit();

            return redirect('/payments')
                ->with('success', 'Payment updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update payment. Please try again.']);
        }
    }
}
