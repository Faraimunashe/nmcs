<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\ConferenceFee;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalStudents = Student::count();
        $totalUsers = User::count();
        
        $conferenceFee = ConferenceFee::getActiveFee();
        $conferenceFeeAmount = $conferenceFee ? $conferenceFee->amount : 0;
        
        $studentsByGender = Student::get()
            ->groupBy(function ($student) {
                return $student->gender instanceof \BackedEnum ? $student->gender->value : $student->gender;
            })
            ->map(function ($group, $gender) {
                return [
                    'label' => $gender,
                    'value' => $group->count()
                ];
            })
            ->values();
        
        $studentsByRegion = Student::with('institution.region')
            ->get()
            ->groupBy(function ($student) {
                return $student->institution?->region?->name ?? 'Unknown';
            })
            ->map(function ($group, $region) {
                return [
                    'label' => $region,
                    'value' => $group->count()
                ];
            })
            ->sortByDesc('value')
            ->values();
        
        $studentsByInstitution = Student::with('institution')
            ->get()
            ->groupBy(function ($student) {
                return $student->institution?->name ?? 'Unknown';
            })
            ->map(function ($group, $institution) {
                return [
                    'label' => $institution,
                    'value' => $group->count()
                ];
            })
            ->sortByDesc('value')
            ->take(10)
            ->values();
        
        $studentsWithCompletePayments = 0;
        $studentsWithPartialPayments = 0;
        $studentsWithNoPayments = 0;
        
        Student::with('payments')->chunk(100, function ($students) use (&$studentsWithCompletePayments, &$studentsWithPartialPayments, &$studentsWithNoPayments, $conferenceFeeAmount) {
            foreach ($students as $student) {
                $totalPaid = $student->payments
                    ->where('status', PaymentStatus::APPROVED)
                    ->sum('final_amount');
                
                if ($totalPaid >= $conferenceFeeAmount) {
                    $studentsWithCompletePayments++;
                } elseif ($totalPaid > 0) {
                    $studentsWithPartialPayments++;
                } else {
                    $studentsWithNoPayments++;
                }
            }
        });
        
        $studentsWithDietaryIssues = Student::whereHas('dietaries')->count();
        $studentsWithChronicConditions = Student::whereHas('chronicConditions')->count();
        $studentsWithDisabilities = Student::whereHas('disabilities')->count();
        
        $paymentStatusBreakdown = [
            ['label' => 'Fully Paid', 'value' => $studentsWithCompletePayments],
            ['label' => 'Partial Payment', 'value' => $studentsWithPartialPayments],
            ['label' => 'No Payment', 'value' => $studentsWithNoPayments],
        ];
        
        $healthIssuesBreakdown = [
            ['label' => 'Dietary Issues', 'value' => $studentsWithDietaryIssues],
            ['label' => 'Chronic Conditions', 'value' => $studentsWithChronicConditions],
            ['label' => 'Disabilities', 'value' => $studentsWithDisabilities],
        ];
        
        $recentRegistrations = Student::with('user', 'institution.region')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->firstnames . ' ' . $student->surname,
                    'email' => $student->user->email ?? 'N/A',
                    'institution' => $student->institution?->name ?? 'N/A',
                    'region' => $student->institution?->region?->name ?? 'N/A',
                    'registered_at' => $student->created_at->format('Y-m-d H:i'),
                ];
            });
        
        $pendingPaymentsList = Payment::with(['student', 'paymentMethod', 'paymentRecipient'])
            ->where('status', PaymentStatus::PENDING)
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'student_name' => $payment->student->firstnames . ' ' . $payment->student->surname,
                    'amount' => number_format($payment->amount, 2),
                    'final_amount' => number_format($payment->final_amount, 2),
                    'purpose' => $payment->purpose instanceof \BackedEnum ? $payment->purpose->value : $payment->purpose,
                    'method' => $payment->paymentMethod->name ?? 'N/A',
                    'reference' => $payment->reference,
                    'date' => $payment->payment_date->format('Y-m-d'),
                ];
            });

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_students' => $totalStudents,
                'total_users' => $totalUsers,
                'fully_paid' => $studentsWithCompletePayments,
                'partial_payment' => $studentsWithPartialPayments,
                'no_payment' => $studentsWithNoPayments,
                'dietary_issues' => $studentsWithDietaryIssues,
                'chronic_conditions' => $studentsWithChronicConditions,
                'disabilities' => $studentsWithDisabilities,
            ],
            'charts' => [
                'students_by_gender' => $studentsByGender,
                'students_by_region' => $studentsByRegion,
                'students_by_institution' => $studentsByInstitution,
                'payment_status' => $paymentStatusBreakdown,
                'health_issues' => $healthIssuesBreakdown,
            ],
            'recent_registrations' => $recentRegistrations,
            'pending_payments' => $pendingPaymentsList,
        ]);
    }
}
