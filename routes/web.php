<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login');

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register');


Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/attendants/create', [\App\Http\Controllers\StudentController::class, 'create'])->name('attendants.create');
    Route::post('/attendants', [\App\Http\Controllers\StudentController::class, 'store'])->name('attendants.store');
});

Route::middleware(['auth', 'require.student'])->group(function () {
    Route::get('/dashboard', [AuthenticationController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/user', [DashboardController::class, 'index'])->name('dashboard.user');

    Route::get('/attendants', [\App\Http\Controllers\StudentController::class, 'index'])->name('attendants.index');
    Route::get('/attendants/{id}', [\App\Http\Controllers\StudentController::class, 'show'])->name('attendants.show');
    Route::get('/attendants/{id}/card', [\App\Http\Controllers\StudentController::class, 'downloadCard'])->name('attendants.card');
    Route::resource('payments', \App\Http\Controllers\PaymentController::class)->only(['index', 'create', 'store', 'edit', 'update'])->names('payments');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboards', [AdminDashboardController::class, 'index'])->name('admin.dashboards');

    Route::get('/attendants', [\App\Http\Controllers\Admin\AttendantController::class, 'index'])->name('admin.attendants.index');
    Route::get('/attendants/export', [\App\Http\Controllers\Admin\AttendantController::class, 'export'])->name('admin.attendants.export');
    Route::get('/attendants/{id}', [\App\Http\Controllers\Admin\AttendantController::class, 'show'])->name('admin.attendants.show');
    Route::get('/attendants/health/dietary', [\App\Http\Controllers\Admin\AttendantController::class, 'dietary'])->name('admin.attendants.dietary');
    Route::get('/attendants/health/chronic', [\App\Http\Controllers\Admin\AttendantController::class, 'chronic'])->name('admin.attendants.chronic');
    Route::get('/attendants/health/disabilities', [\App\Http\Controllers\Admin\AttendantController::class, 'disabilities'])->name('admin.attendants.disabilities');
    Route::get('/attendants/payments/fully-paid', [\App\Http\Controllers\Admin\AttendantController::class, 'fullyPaid'])->name('admin.attendants.fully-paid');
    Route::get('/attendants/payments/partial', [\App\Http\Controllers\Admin\AttendantController::class, 'partial'])->name('admin.attendants.partial');
    Route::get('/attendants/payments/none', [\App\Http\Controllers\Admin\AttendantController::class, 'noPayment'])->name('admin.attendants.no-payment');
    Route::get('/attendants/{id}/transactions', [\App\Http\Controllers\Admin\AttendantController::class, 'transactions'])->name('admin.attendants.transactions');
    Route::get('/attendants/verify/{id}', [\App\Http\Controllers\Admin\AttendantController::class, 'verify'])->name('admin.attendants.verify');

    Route::get('/payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('admin.payments.index');
    Route::post('/payments/{id}/approve', [\App\Http\Controllers\Admin\PaymentController::class, 'approve'])->name('admin.payments.approve');
    Route::post('/payments/{id}/reject', [\App\Http\Controllers\Admin\PaymentController::class, 'reject'])->name('admin.payments.reject');

    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings.index');

    Route::resource('settings/conference-fees', \App\Http\Controllers\Admin\ConferenceFeeController::class)
        ->only(['index', 'store', 'destroy'])
        ->names('admin.settings.conference-fees');

    Route::resource('settings/payment-charges', \App\Http\Controllers\Admin\PaymentChargeController::class)
        ->only(['index', 'store', 'destroy'])
        ->names('admin.settings.payment-charges');

    Route::resource('settings/payment-methods', \App\Http\Controllers\Admin\PaymentMethodController::class)
        ->only(['index', 'store', 'destroy'])
        ->names('admin.settings.payment-methods');

    Route::resource('settings/payment-recipients', \App\Http\Controllers\Admin\PaymentRecipientController::class)
        ->only(['index', 'store', 'destroy'])
        ->names('admin.settings.payment-recipients');

    Route::resource('settings/memberships', \App\Http\Controllers\Admin\MembershipController::class)
        ->only(['index', 'store', 'destroy'])
        ->names('admin.settings.memberships');

    Route::resource('settings/regions', \App\Http\Controllers\Admin\RegionController::class)
        ->only(['index', 'store', 'destroy'])
        ->names('admin.settings.regions');

    Route::resource('settings/institutions', \App\Http\Controllers\Admin\InstitutionController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.settings.institutions');
    // Allow POST with _method=PUT for file uploads from Inertia
    Route::post('settings/institutions/{institution}', [\App\Http\Controllers\Admin\InstitutionController::class, 'update'])
        ->name('admin.settings.institutions.update.post');

    Route::resource('settings/disabilities', \App\Http\Controllers\Admin\DisabilityController::class)
        ->only(['index', 'store', 'destroy'])
        ->names('admin.settings.disabilities');

    Route::resource('settings/dietaries', \App\Http\Controllers\Admin\DietaryController::class)
        ->only(['index', 'store', 'destroy'])
        ->names('admin.settings.dietaries');

    Route::resource('settings/chronic-conditions', \App\Http\Controllers\Admin\ChronicConditionController::class)
        ->only(['index', 'store', 'destroy'])
        ->names('admin.settings.chronic-conditions');

    Route::resource('users', UserController::class)->names('admin.users');
});
