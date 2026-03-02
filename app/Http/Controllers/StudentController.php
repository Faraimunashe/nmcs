<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Enums\PaymentStatus;
use App\Models\ChronicCondition;
use App\Models\ConferenceFee;
use App\Models\Dietary;
use App\Models\Disability;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentRecipient;
use App\Models\Region;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Student::where('user_id', $user->id)
            ->with(['phones', 'membership', 'nextOfKins']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstnames', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('nationalid', 'like', "%{$search}%")
                    ->orWhereHas('phones', function ($phoneQuery) use ($search) {
                        $phoneQuery->where('phone', 'like', "%{$search}%");
                    });
            });
        }

        $students = $query->latest()->paginate(15);

        return Inertia::render('Attendants/Index', [
            'students' => $students,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        $memberships = Membership::all();
        $regions = Region::with('institutions')->get();
        $disabilities = Disability::all();
        $dietaries = Dietary::all();
        $chronicConditions = ChronicCondition::all();
        $paymentMethods = PaymentMethod::where('is_active', true)->with('paymentCharge')->get();
        $paymentRecipients = PaymentRecipient::where('is_active', true)->get();

        return Inertia::render('Attendants/Create', [
            'memberships' => $memberships,
            'regions' => $regions,
            'disabilities' => $disabilities,
            'dietaries' => $dietaries,
            'chronicConditions' => $chronicConditions,
            'paymentMethods' => $paymentMethods,
            'paymentRecipients' => $paymentRecipients,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstnames' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:MALE,FEMALE'],
            'address' => ['nullable', 'string'],
            'nationalid' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$/i',
                'unique:students,nationalid',
            ],
            'institution_id' => ['nullable', 'exists:institutions,id'],
            'phones' => ['required', 'array', 'min:1'],
            'phones.*' => [
                'required',
                'string',
                'regex:/^\\+263[0-9]{9}$/',
            ],
            'membership_id' => ['nullable', 'exists:memberships,id'],
            'next_of_kins' => ['nullable', 'array'],
            'next_of_kins.*.relationship' => ['nullable', 'string'],
            'next_of_kins.*.fullname' => ['nullable', 'string'],
            'next_of_kins.*.phone' => ['nullable', 'string'],
            'disability_ids' => ['nullable', 'array'],
            'disability_ids.*' => ['exists:disabilities,id'],
            'dietary_ids' => ['nullable', 'array'],
            'dietary_ids.*' => ['exists:dietaries,id'],
            'chronic_condition_ids' => ['nullable', 'array'],
            'chronic_condition_ids.*' => ['exists:chronic_conditions,id'],
        ]);

        DB::beginTransaction();
        try {
            $student = Student::create([
                'user_id' => $request->user()->id,
                'firstnames' => $validated['firstnames'],
                'surname' => $validated['surname'],
                'gender' => Gender::from($validated['gender']),
                'address' => $validated['address'] ?? null,
                'nationalid' => $validated['nationalid'] ?? null,
                'institution_id' => $validated['institution_id'] ?? null,
                'membership_id' => $validated['membership_id'] ?? null,
            ]);

            foreach ($validated['phones'] as $phone) {
                if (!empty(trim($phone))) {
                    $student->phones()->create(['phone' => trim($phone)]);
                }
            }

            if (!empty($validated['next_of_kins'])) {
                foreach ($validated['next_of_kins'] as $nok) {
                    if (!empty($nok['relationship']) || !empty($nok['fullname']) || !empty($nok['phone'])) {
                        $student->nextOfKins()->create([
                            'relationship' => $nok['relationship'] ?? '',
                            'fullname' => $nok['fullname'] ?? '',
                            'phone' => $nok['phone'] ?? '',
                        ]);
                    }
                }
            }

            if (!empty($validated['disability_ids'])) {
                $student->disabilities()->attach($validated['disability_ids']);
            }

            if (!empty($validated['dietary_ids'])) {
                $student->dietaries()->attach($validated['dietary_ids']);
            }

            if (!empty($validated['chronic_condition_ids'])) {
                $student->chronicConditions()->attach($validated['chronic_condition_ids']);
            }

            DB::commit();

            return redirect('/dashboard')
                ->with('success', 'Profile completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to capture attendant: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to capture attendant: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $student = Student::where('user_id', auth()->id())
            ->with([
                'phones',
                'membership',
                'nextOfKins',
                'institution.region',
                'disabilities',
                'dietaries',
                'chronicConditions',
            ])
            ->findOrFail($id);

        return Inertia::render('Attendants/Show', [
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
                    'logo' => $student->institution->logo,
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
        ]);
    }

    public function downloadCard($id)
    {
        $student = Student::where('user_id', auth()->id())
            ->with([
                'institution.region',
                'payments',
            ])
            ->findOrFail($id);

        $conferenceFee = ConferenceFee::getActiveFee();
        $conferenceFeeAmount = $conferenceFee ? $conferenceFee->amount : 0;

        $totalPaid = Payment::where('student_id', $student->id)
            ->where('status', PaymentStatus::APPROVED)
            ->sum('final_amount');

        $balance = $conferenceFeeAmount - $totalPaid;

        $verificationUrl = route('admin.attendants.verify', $student->id);

        $qrSvg = QrCode::format('svg')
            ->size(180)
            ->margin(0)
            ->generate($verificationUrl);
        $qrImageBase64 = base64_encode($qrSvg);

        $logoPath = public_path('images/nmcs.jpeg');
        $logoBase64 = file_exists($logoPath)
            ? base64_encode(file_get_contents($logoPath))
            : null;

        $institutionLogoBase64 = null;
        if ($student->institution && $student->institution->logo) {
            $instPath = public_path('storage/' . $student->institution->logo);
            if (file_exists($instPath)) {
                $institutionLogoBase64 = base64_encode(file_get_contents($instPath));
            }
        }

        $pdf = Pdf::loadView('attendants.card-pdf', [
            'fullName' => $student->firstnames . ' ' . $student->surname,
            'gender' => $student->gender instanceof \BackedEnum ? $student->gender->value : $student->gender,
            'nationalId' => $student->nationalid,
            'region' => $student->institution?->region?->name,
            'institution' => $student->institution?->name,
            'balance' => $balance,
            'conferenceFee' => $conferenceFeeAmount,
            'verificationUrl' => $verificationUrl,
            'qrImageBase64' => $qrImageBase64,
            'logoBase64' => $logoBase64,
            'institutionLogoBase64' => $institutionLogoBase64,
        ])->setPaper('a4', 'portrait');

        $filename = 'attendant-card-' . $student->id . '.pdf';

        return $pdf->download($filename);
    }
}
