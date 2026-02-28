<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\ConferenceFee;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $student = Student::where('user_id', $user->id)
            ->with([
                'phones',
                'membership',
                'nextOfKins',
                'institution.region',
                'disabilities',
                'dietaries',
                'chronicConditions',
            ])
            ->first();

        if (!$student) {
            return redirect('/attendants/create');
        }

        $totalPaid = Payment::where('student_id', $student->id)
            ->where('status', PaymentStatus::APPROVED)
            ->sum('final_amount');
        $totalPending = Payment::where('student_id', $student->id)
            ->where('status', PaymentStatus::PENDING)
            ->sum('final_amount');
        
        $conferenceFee = ConferenceFee::getActiveFee();
        $conferenceFeeAmount = $conferenceFee ? $conferenceFee->amount : 0;
        $balance = $conferenceFeeAmount - $totalPaid;


        return Inertia::render('Dashboard/Index', [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'student' => [
                'id' => $student->id,
                'firstnames' => $student->firstnames,
                'surname' => $student->surname,
                'gender' => $student->gender instanceof \BackedEnum ? $student->gender->value : $student->gender,
                'address' => $student->address,
                'nationalid' => $student->nationalid,
                'phones' => $student->phones->map(fn($p) => $p->phone),
                'membership' => $student->membership ? [
                    'status' => $student->membership->status instanceof \BackedEnum ? $student->membership->status->value : $student->membership->status,
                    'description' => $student->membership->description,
                ] : null,
                'institution' => $student->institution ? [
                    'name' => $student->institution->name,
                    'code' => $student->institution->code,
                    'region' => $student->institution->region->name ?? null,
                ] : null,
                'next_of_kins' => $student->nextOfKins->map(function ($nok) {
                    return [
                        'relationship' => $nok->relationship,
                        'fullname' => $nok->fullname,
                        'phone' => $nok->phone,
                    ];
                }),
                'disabilities' => $student->disabilities->pluck('name'),
                'dietaries' => $student->dietaries->pluck('name'),
                'chronic_conditions' => $student->chronicConditions->pluck('name'),
            ],
            'paymentBalance' => [
                'total_paid' => number_format($totalPaid, 2),
                'total_pending' => number_format($totalPending, 2),
            ],
            'balanceSummary' => [
                'balance' => number_format($balance, 2),
                'conference_fee' => number_format($conferenceFeeAmount, 2),
            ],
        ]);
    }
}
