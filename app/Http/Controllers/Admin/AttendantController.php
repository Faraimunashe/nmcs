<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Exports\AttendantsExport;
use App\Http\Controllers\Controller;
use App\Models\ConferenceFee;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class AttendantController extends Controller
{
    public function index(Request $request)
    {
        $conferenceFee = ConferenceFee::getActiveFee();
        $conferenceFeeAmount = $conferenceFee ? $conferenceFee->amount : 0;

        $query = Student::with(['user', 'institution.region', 'membership', 'payments']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstnames', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%")
                  ->orWhere('nationalid', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->region) {
            $query->whereHas('institution.region', function ($q) use ($request) {
                $q->where('id', $request->region);
            });
        }

        if ($request->membership) {
            $query->where('membership_id', $request->membership);
        }

        $students = $query->latest()->paginate(20);

        return Inertia::render('Admin/Attendants/Index', [
            'students' => [
                'data' => $students->map(function ($student) use ($conferenceFeeAmount) {
                    $totalPaid = $student->payments
                        ->where('status', PaymentStatus::APPROVED)
                        ->sum('final_amount');
                    $balance = $conferenceFeeAmount - $totalPaid;
                    
                    return [
                        'id' => $student->id,
                        'firstnames' => $student->firstnames,
                        'surname' => $student->surname,
                        'email' => $student->user->email ?? 'N/A',
                        'gender' => $student->gender instanceof \BackedEnum ? $student->gender->value : $student->gender,
                        'institution' => $student->institution?->name ?? 'N/A',
                        'region' => $student->institution?->region?->name ?? 'N/A',
                        'membership' => $student->membership?->status instanceof \BackedEnum 
                            ? $student->membership->status->value 
                            : ($student->membership?->status ?? 'N/A'),
                        'total_paid' => number_format($totalPaid, 2),
                        'balance' => number_format($balance, 2),
                        'registered_at' => $student->created_at->format('Y-m-d H:i'),
                    ];
                }),
                'links' => $students->links(),
                'from' => $students->firstItem(),
                'to' => $students->lastItem(),
                'total' => $students->total(),
            ],
            'filters' => $request->only(['search', 'region', 'membership']),
        ]);
    }

    public function show($id)
    {
        $student = Student::with([
            'user',
            'phones',
            'membership',
            'nextOfKins',
            'institution.region',
            'disabilities',
            'dietaries',
            'chronicConditions',
            'payments.paymentMethod',
        ])->findOrFail($id);

        return Inertia::render('Admin/Attendants/Show', [
            'student' => [
                'id' => $student->id,
                'firstnames' => $student->firstnames,
                'surname' => $student->surname,
                'gender' => $student->gender instanceof \BackedEnum ? $student->gender->value : $student->gender,
                'address' => $student->address,
                'nationalid' => $student->nationalid,
                'email' => $student->user->email ?? 'N/A',
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
                'payments' => $student->payments->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'amount' => number_format($payment->amount, 2),
                        'final_amount' => number_format($payment->final_amount, 2),
                        'purpose' => $payment->purpose instanceof \BackedEnum ? $payment->purpose->value : $payment->purpose,
                        'status' => $payment->status instanceof \BackedEnum ? $payment->status->value : $payment->status,
                        'payment_date' => $payment->payment_date->format('Y-m-d'),
                        'payment_method' => $payment->paymentMethod->name ?? 'N/A',
                    ];
                }),
            ],
        ]);
    }

    public function dietary(Request $request)
    {
        $query = Student::whereHas('dietaries')
            ->with(['user', 'institution.region', 'membership', 'dietaries']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstnames', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%");
                  });
            });
        }

        $students = $query->latest()->paginate(20);

        $conditionStats = \App\Models\Dietary::whereHas('students')
            ->withCount('students')
            ->orderByDesc('students_count')
            ->get()
            ->map(function ($dietary) {
                return [
                    'id' => $dietary->id,
                    'name' => $dietary->name,
                    'count' => $dietary->students_count,
                ];
            });

        return Inertia::render('Admin/Attendants/Health', [
            'title' => 'Students with Dietary Issues',
            'type' => 'dietary',
            'students' => [
                'data' => $students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'firstnames' => $student->firstnames,
                        'surname' => $student->surname,
                        'email' => $student->user->email ?? 'N/A',
                        'institution' => $student->institution?->name ?? 'N/A',
                        'region' => $student->institution?->region?->name ?? 'N/A',
                        'conditions' => $student->dietaries->pluck('name'),
                        'registered_at' => $student->created_at->format('Y-m-d H:i'),
                    ];
                }),
                'links' => $students->links(),
                'from' => $students->firstItem(),
                'to' => $students->lastItem(),
                'total' => $students->total(),
            ],
            'filters' => $request->only(['search']),
            'conditionStats' => $conditionStats,
        ]);
    }

    public function chronic(Request $request)
    {
        $query = Student::whereHas('chronicConditions')
            ->with(['user', 'institution.region', 'membership', 'chronicConditions']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstnames', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%");
                  });
            });
        }

        $students = $query->latest()->paginate(20);

        $conditionStats = \App\Models\ChronicCondition::whereHas('students')
            ->withCount('students')
            ->orderByDesc('students_count')
            ->get()
            ->map(function ($condition) {
                return [
                    'id' => $condition->id,
                    'name' => $condition->name,
                    'count' => $condition->students_count,
                ];
            });

        return Inertia::render('Admin/Attendants/Health', [
            'title' => 'Students with Chronic Conditions',
            'type' => 'chronic',
            'students' => [
                'data' => $students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'firstnames' => $student->firstnames,
                        'surname' => $student->surname,
                        'email' => $student->user->email ?? 'N/A',
                        'institution' => $student->institution?->name ?? 'N/A',
                        'region' => $student->institution?->region?->name ?? 'N/A',
                        'conditions' => $student->chronicConditions->pluck('name'),
                        'registered_at' => $student->created_at->format('Y-m-d H:i'),
                    ];
                }),
                'links' => $students->links(),
                'from' => $students->firstItem(),
                'to' => $students->lastItem(),
                'total' => $students->total(),
            ],
            'filters' => $request->only(['search']),
            'conditionStats' => $conditionStats,
        ]);
    }

    public function disabilities(Request $request)
    {
        $query = Student::whereHas('disabilities')
            ->with(['user', 'institution.region', 'membership', 'disabilities']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstnames', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%");
                  });
            });
        }

        $students = $query->latest()->paginate(20);

        $conditionStats = \App\Models\Disability::whereHas('students')
            ->withCount('students')
            ->orderByDesc('students_count')
            ->get()
            ->map(function ($disability) {
                return [
                    'id' => $disability->id,
                    'name' => $disability->name,
                    'count' => $disability->students_count,
                ];
            });

        return Inertia::render('Admin/Attendants/Health', [
            'title' => 'Students with Disabilities',
            'type' => 'disabilities',
            'students' => [
                'data' => $students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'firstnames' => $student->firstnames,
                        'surname' => $student->surname,
                        'email' => $student->user->email ?? 'N/A',
                        'institution' => $student->institution?->name ?? 'N/A',
                        'region' => $student->institution?->region?->name ?? 'N/A',
                        'conditions' => $student->disabilities->pluck('name'),
                        'registered_at' => $student->created_at->format('Y-m-d H:i'),
                    ];
                }),
                'links' => $students->links(),
                'from' => $students->firstItem(),
                'to' => $students->lastItem(),
                'total' => $students->total(),
            ],
            'filters' => $request->only(['search']),
            'conditionStats' => $conditionStats,
        ]);
    }

    public function fullyPaid(Request $request)
    {
        $conferenceFee = ConferenceFee::getActiveFee();
        $conferenceFeeAmount = $conferenceFee ? $conferenceFee->amount : 0;

        $query = Student::with(['user', 'institution.region', 'payments'])
            ->get()
            ->filter(function ($student) use ($conferenceFeeAmount) {
                $totalPaid = $student->payments
                    ->where('status', PaymentStatus::APPROVED)
                    ->sum('final_amount');
                return $totalPaid >= $conferenceFeeAmount;
            });

        if ($request->search) {
            $search = $request->search;
            $query = $query->filter(function ($student) use ($search) {
                return stripos($student->firstnames . ' ' . $student->surname, $search) !== false
                    || stripos($student->user->email ?? '', $search) !== false;
            });
        }

        $total = $query->count();
        $page = $request->get('page', 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $paginated = $query->slice($offset, $perPage);

        return Inertia::render('Admin/Attendants/Payments', [
            'title' => 'Students with Complete Payments',
            'type' => 'fully-paid',
            'students' => [
                'data' => $paginated->map(function ($student) use ($conferenceFeeAmount) {
                    $totalPaid = $student->payments
                        ->where('status', PaymentStatus::APPROVED)
                        ->sum('final_amount');
                    $balance = $conferenceFeeAmount - $totalPaid;
                    
                    return [
                        'id' => $student->id,
                        'firstnames' => $student->firstnames,
                        'surname' => $student->surname,
                        'email' => $student->user->email ?? 'N/A',
                        'institution' => $student->institution?->name ?? 'N/A',
                        'region' => $student->institution?->region?->name ?? 'N/A',
                        'total_paid' => number_format($totalPaid, 2),
                        'balance' => number_format($balance, 2),
                        'conference_fee' => number_format($conferenceFeeAmount, 2),
                    ];
                })->values(),
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage),
            ],
            'filters' => $request->only(['search']),
        ]);
    }

    public function partial(Request $request)
    {
        $conferenceFee = ConferenceFee::getActiveFee();
        $conferenceFeeAmount = $conferenceFee ? $conferenceFee->amount : 0;

        $query = Student::with(['user', 'institution.region', 'payments'])
            ->get()
            ->filter(function ($student) use ($conferenceFeeAmount) {
                $totalPaid = $student->payments
                    ->where('status', PaymentStatus::APPROVED)
                    ->sum('final_amount');
                return $totalPaid > 0 && $totalPaid < $conferenceFeeAmount;
            });

        if ($request->search) {
            $search = $request->search;
            $query = $query->filter(function ($student) use ($search) {
                return stripos($student->firstnames . ' ' . $student->surname, $search) !== false
                    || stripos($student->user->email ?? '', $search) !== false;
            });
        }

        $total = $query->count();
        $page = $request->get('page', 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $paginated = $query->slice($offset, $perPage);

        return Inertia::render('Admin/Attendants/Payments', [
            'title' => 'Students with Partial Payments',
            'type' => 'partial',
            'students' => [
                'data' => $paginated->map(function ($student) use ($conferenceFeeAmount) {
                    $totalPaid = $student->payments
                        ->where('status', PaymentStatus::APPROVED)
                        ->sum('final_amount');
                    $balance = $conferenceFeeAmount - $totalPaid;
                    
                    return [
                        'id' => $student->id,
                        'firstnames' => $student->firstnames,
                        'surname' => $student->surname,
                        'email' => $student->user->email ?? 'N/A',
                        'institution' => $student->institution?->name ?? 'N/A',
                        'region' => $student->institution?->region?->name ?? 'N/A',
                        'total_paid' => number_format($totalPaid, 2),
                        'balance' => number_format($balance, 2),
                        'conference_fee' => number_format($conferenceFeeAmount, 2),
                    ];
                })->values(),
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage),
            ],
            'filters' => $request->only(['search']),
        ]);
    }

    public function noPayment(Request $request)
    {
        $conferenceFee = ConferenceFee::getActiveFee();
        $conferenceFeeAmount = $conferenceFee ? $conferenceFee->amount : 0;

        $query = Student::with(['user', 'institution.region', 'payments'])
            ->get()
            ->filter(function ($student) {
                $totalPaid = $student->payments
                    ->where('status', PaymentStatus::APPROVED)
                    ->sum('final_amount');
                return $totalPaid == 0;
            });

        if ($request->search) {
            $search = $request->search;
            $query = $query->filter(function ($student) use ($search) {
                return stripos($student->firstnames . ' ' . $student->surname, $search) !== false
                    || stripos($student->user->email ?? '', $search) !== false;
            });
        }

        $total = $query->count();
        $page = $request->get('page', 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $paginated = $query->slice($offset, $perPage);

        return Inertia::render('Admin/Attendants/Payments', [
            'title' => 'Students with No Payments',
            'type' => 'none',
            'students' => [
                'data' => $paginated->map(function ($student) use ($conferenceFeeAmount) {
                    return [
                        'id' => $student->id,
                        'firstnames' => $student->firstnames,
                        'surname' => $student->surname,
                        'email' => $student->user->email ?? 'N/A',
                        'institution' => $student->institution?->name ?? 'N/A',
                        'region' => $student->institution?->region?->name ?? 'N/A',
                        'total_paid' => '0.00',
                        'balance' => number_format($conferenceFeeAmount, 2),
                        'conference_fee' => number_format($conferenceFeeAmount, 2),
                    ];
                })->values(),
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage),
            ],
            'filters' => $request->only(['search']),
        ]);
    }

    public function transactions($id)
    {
        $student = Student::with([
            'user',
            'payments.paymentMethod',
            'payments.paymentRecipient',
            'payments.approvedBy',
            'payments.rejectedBy',
        ])->findOrFail($id);

        $conferenceFee = ConferenceFee::getActiveFee();
        $conferenceFeeAmount = $conferenceFee ? $conferenceFee->amount : 0;

        $totalPaid = $student->payments
            ->where('status', PaymentStatus::APPROVED)
            ->sum('final_amount');
        
        $totalPending = $student->payments
            ->where('status', PaymentStatus::PENDING)
            ->sum('final_amount');
        
        $balance = $conferenceFeeAmount - $totalPaid;

        return Inertia::render('Admin/Attendants/Transactions', [
            'student' => [
                'id' => $student->id,
                'name' => $student->firstnames . ' ' . $student->surname,
                'email' => $student->user->email ?? 'N/A',
                'institution' => $student->institution?->name ?? 'N/A',
                'region' => $student->institution?->region?->name ?? 'N/A',
            ],
            'balance' => [
                'conference_fee' => number_format($conferenceFeeAmount, 2),
                'total_paid' => number_format($totalPaid, 2),
                'total_pending' => number_format($totalPending, 2),
                'balance' => number_format($balance, 2),
            ],
                'payments' => $student->payments->sortByDesc('payment_date')->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => number_format($payment->amount, 2),
                    'final_amount' => number_format($payment->final_amount, 2),
                    'purpose' => $payment->purpose instanceof \BackedEnum ? $payment->purpose->value : $payment->purpose,
                    'status' => $payment->status instanceof \BackedEnum ? $payment->status->value : $payment->status,
                    'reference' => $payment->reference,
                    'payment_date' => $payment->payment_date->format('Y-m-d'),
                    'created_at' => $payment->created_at->format('Y-m-d H:i'),
                    'payment_method' => $payment->paymentMethod->name ?? 'N/A',
                    'payment_recipient' => $payment->paymentRecipient->name ?? null,
                    'approved_by' => $payment->approvedBy ? $payment->approvedBy->name : null,
                    'approved_at' => $payment->approved_at ? $payment->approved_at->format('Y-m-d H:i') : null,
                    'rejected_by' => $payment->rejectedBy ? $payment->rejectedBy->name : null,
                    'rejected_at' => $payment->rejected_at ? $payment->rejected_at->format('Y-m-d H:i') : null,
                    'rejection_reason' => $payment->rejection_reason,
                ];
            })->values(),
        ]);
    }

    public function verify($id)
    {
        $student = Student::with([
            'user',
            'phones',
            'membership',
            'nextOfKins',
            'institution.region',
            'disabilities',
            'dietaries',
            'chronicConditions',
            'payments.paymentMethod',
        ])->findOrFail($id);

        $conferenceFee = ConferenceFee::getActiveFee();
        $conferenceFeeAmount = $conferenceFee ? $conferenceFee->amount : 0;

        $totalPaid = $student->payments
            ->where('status', PaymentStatus::APPROVED)
            ->sum('final_amount');

        $totalPending = $student->payments
            ->where('status', PaymentStatus::PENDING)
            ->sum('final_amount');

        $balance = $conferenceFeeAmount - $totalPaid;

        return Inertia::render('Admin/Attendants/Verify', [
            'student' => [
                'id' => $student->id,
                'firstnames' => $student->firstnames,
                'surname' => $student->surname,
                'gender' => $student->gender instanceof \BackedEnum ? $student->gender->value : $student->gender,
                'address' => $student->address,
                'nationalid' => $student->nationalid,
                'email' => $student->user->email ?? 'N/A',
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
                'payments' => $student->payments->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'amount' => number_format($payment->amount, 2),
                        'final_amount' => number_format($payment->final_amount, 2),
                        'purpose' => $payment->purpose instanceof \BackedEnum ? $payment->purpose->value : $payment->purpose,
                        'status' => $payment->status instanceof \BackedEnum ? $payment->status->value : $payment->status,
                        'payment_date' => $payment->payment_date->format('Y-m-d'),
                        'payment_method' => $payment->paymentMethod->name ?? 'N/A',
                    ];
                }),
            ],
            'balance' => [
                'conference_fee' => number_format($conferenceFeeAmount, 2),
                'total_paid' => number_format($totalPaid, 2),
                'total_pending' => number_format($totalPending, 2),
                'balance' => number_format($balance, 2),
            ],
        ]);
    }

    public function export(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string', 'in:all,fully-paid,partial,none,dietary,chronic,disabilities'],
            'format' => ['required', 'string', 'in:excel,pdf'],
        ]);

        $search = $request->get('search', '');
        $data = $this->getExportData($request->type, $search);

        $slug = str_replace(' ', '-', strtolower($data['title']));
        $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
        $date = now()->format('Y-m-d');

        if ($request->get('format') === 'excel') {
            $filename = "attendants-{$slug}-{$date}.xlsx";
            $export = new AttendantsExport($data['headings'], $data['rows']);
            return Excel::download($export, $filename);
        }

        $filename = "attendants-{$slug}-{$date}.pdf";
        $pdf = Pdf::loadView('admin.attendants.export-pdf', [
            'title' => $data['title'],
            'headings' => $data['headings'],
            'rows' => $data['rows'],
        ])->setPaper('a4', 'landscape');
        return $pdf->download($filename);
    }

    protected function getExportData(string $type, string $search): array
    {
        $conferenceFee = ConferenceFee::getActiveFee();
        $conferenceFeeAmount = $conferenceFee ? (float) $conferenceFee->amount : 0;

        if ($type === 'all') {
            $query = Student::with(['user', 'institution.region', 'membership', 'payments']);
            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('firstnames', 'like', "%{$search}%")
                        ->orWhere('surname', 'like', "%{$search}%")
                        ->orWhere('nationalid', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($uq) => $uq->where('email', 'like', "%{$search}%"));
                });
            }
            $students = $query->latest()->get();
            $headings = ['First Name(s)', 'Surname', 'Email', 'Gender', 'Institution', 'Region', 'Membership', 'Total Paid ($)', 'Balance ($)', 'Registered At'];
            $rows = $students->map(function ($student) use ($conferenceFeeAmount) {
                $totalPaid = $student->payments->where('status', PaymentStatus::APPROVED)->sum('final_amount');
                $balance = $conferenceFeeAmount - $totalPaid;
                return [
                    $student->firstnames,
                    $student->surname,
                    $student->user->email ?? 'N/A',
                    $student->gender instanceof \BackedEnum ? $student->gender->value : (string) $student->gender,
                    $student->institution?->name ?? 'N/A',
                    $student->institution?->region?->name ?? 'N/A',
                    $student->membership && $student->membership->status instanceof \BackedEnum ? $student->membership->status->value : ($student->membership?->status ?? 'N/A'),
                    number_format($totalPaid, 2),
                    number_format($balance, 2),
                    $student->created_at->format('Y-m-d H:i'),
                ];
            })->all();
            return ['title' => 'All Attendants', 'headings' => $headings, 'rows' => $rows];
        }

        if (in_array($type, ['fully-paid', 'partial', 'none'], true)) {
            $students = Student::with(['user', 'institution.region', 'payments'])->get();
            if ($type === 'fully-paid') {
                $students = $students->filter(fn ($s) => $s->payments->where('status', PaymentStatus::APPROVED)->sum('final_amount') >= $conferenceFeeAmount);
            } elseif ($type === 'partial') {
                $students = $students->filter(fn ($s) => ($total = $s->payments->where('status', PaymentStatus::APPROVED)->sum('final_amount')) > 0 && $total < $conferenceFeeAmount);
            } else {
                $students = $students->filter(fn ($s) => $s->payments->where('status', PaymentStatus::APPROVED)->sum('final_amount') == 0);
            }
            if ($search !== '') {
                $students = $students->filter(fn ($s) => stripos($s->firstnames . ' ' . $s->surname, $search) !== false || stripos($s->user->email ?? '', $search) !== false);
            }
            $students = $students->values();
            $titles = ['fully-paid' => 'Attendants with Complete Payment', 'partial' => 'Attendants with Partial Payment', 'none' => 'Attendants with No Payment'];
            $headings = ['First Name(s)', 'Surname', 'Email', 'Institution', 'Region', 'Total Paid ($)', 'Balance ($)', 'Conference Fee ($)', 'Registered At'];
            $rows = $students->map(function ($student) use ($conferenceFeeAmount) {
                $totalPaid = $student->payments->where('status', PaymentStatus::APPROVED)->sum('final_amount');
                $balance = $conferenceFeeAmount - $totalPaid;
                return [
                    $student->firstnames,
                    $student->surname,
                    $student->user->email ?? 'N/A',
                    $student->institution?->name ?? 'N/A',
                    $student->institution?->region?->name ?? 'N/A',
                    number_format($totalPaid, 2),
                    number_format($balance, 2),
                    number_format($conferenceFeeAmount, 2),
                    $student->created_at->format('Y-m-d H:i'),
                ];
            })->all();
            return ['title' => $titles[$type], 'headings' => $headings, 'rows' => $rows];
        }

        $relation = ['dietary' => 'dietaries', 'chronic' => 'chronicConditions', 'disabilities' => 'disabilities'][$type];
        $query = Student::whereHas($relation)->with(['user', 'institution.region', $relation]);
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('firstnames', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($uq) => $uq->where('email', 'like', "%{$search}%"));
            });
        }
        $students = $query->latest()->get();
        $conditionLabel = ['dietary' => 'Dietary Requirements', 'chronic' => 'Chronic Conditions', 'disabilities' => 'Disabilities'][$type];
        $titles = ['dietary' => 'Attendants with Dietary Issues', 'chronic' => 'Attendants with Chronic Conditions', 'disabilities' => 'Attendants with Disabilities'];
        $headings = ['First Name(s)', 'Surname', 'Email', 'Institution', 'Region', $conditionLabel, 'Registered At'];
        $rows = $students->map(function ($student) use ($relation) {
            $conditions = $student->$relation->pluck('name')->implode(', ');
            return [
                $student->firstnames,
                $student->surname,
                $student->user->email ?? 'N/A',
                $student->institution?->name ?? 'N/A',
                $student->institution?->region?->name ?? 'N/A',
                $conditions,
                $student->created_at->format('Y-m-d H:i'),
            ];
        })->all();
        return ['title' => $titles[$type], 'headings' => $headings, 'rows' => $rows];
    }
}
