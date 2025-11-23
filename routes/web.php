<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\MilkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

// Load Laravel Breeze/Fortify auth routes (login, logout, password reset, etc.)
require __DIR__.'/auth.php';

// ====================================================================
// CUSTOM GUEST ROUTES
// ====================================================================

Route::middleware('guest')->group(function () {

    // Custom: First-time password setup for new donors
    Route::get('/first-time-password', [NewPasswordController::class, 'createFirstTime'])
        ->name('password.first-time');
    Route::post('/first-time-password', [NewPasswordController::class, 'storeFirstTime'])
        ->name('password.set.first-time');

    // Custom Register Page
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register.donor.store');
});

// Override default login page (show your beautiful custom login)
Route::get('/login', fn() => view('auth.login'))
    ->middleware('guest')
    ->name('login');

// Home
Route::get('/', fn() => view('welcome'))->name('home');

// ====================================================================
// AUTHENTICATED ROUTES
// ====================================================================

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Role-based Dashboards (keep only ONE of each)
    Route::view('/hmmc/dashboard', 'hmmc.hmmc_dashboard')->name('hmmc.dashboard');
    Route::view('/nurse/dashboard', 'nurse.nurse_dashboard')->name('nurse.dashboard');
    Route::view('/doctor/dashboard', 'doctor.doctor_dashboard')->name('doctor.dashboard');
    Route::view('/labtech/dashboard', 'labtech.labtech_dashboard')->name('labtech.dashboard');
    Route::view('/shariah/dashboard', 'shariah.shariah_dashboard')->name('shariah.dashboard');
    Route::view('/parent/dashboard', 'parent.parent_dashboard')->name('parent.dashboard');
    Route::view('/donor/dashboard', 'donor.donor_dashboard')->name('donor.dashboard');

    // Extra Profile Views
    Route::view('/donor/edit-profile', 'donor.donor_edit-profile')->name('donor.edit-profile');
    Route::view('/doctor/edit-profile', 'doctor.doctor_edit-profile')->name('doctor.edit-profile');
    Route::view('/shariah/profile', 'shariah.shariah_profile')->name('shariah.profile');

    // ====================================================================
    // USER MANAGEMENT (HMMC Admin)
    // ====================================================================
    Route::prefix('hmmc')->name('hmmc.')->group(function () {
        Route::get('/manage-users', [UserController::class, 'index'])->name('manage-users');
        Route::get('/create-new-user/{role}', [UserController::class, 'create'])->name('create-new-user');
        Route::post('/store-new-user', [UserController::class, 'store'])->name('store-user');

        Route::get('/users/{role}/{id}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{role}/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{role}/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{role}/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::post('/send-credentials', [UserController::class, 'sendCredentials'])
            ->name('send-credentials');
    });

    Route::post('/hmmc/validate-user-field', [UserController::class, 'validateField'])
        ->name('hmmc.validate-user-field');

    // ====================================================================
    // LAB TECH - MILK PROCESSING
    // ====================================================================
    // LAB TECH - MILK PROCESSING
    Route::prefix('labtech')->name('labtech.')->group(function () {
        Route::get('/manage-milk-records', [MilkController::class, 'viewMilk'])
            ->name('labtech_manage-milk-records');
            
        Route::post('/manage-milk-records/store', [MilkController::class, 'storeMilkRecord'])
            ->name('labtech_store-manage-milk-records');

        Route::get('/process-milk/{milk}', [MilkController::class, 'processMilk'])
            ->name('labtech_process-milk');

        Route::put('/process-milk/{milk}/update', [MilkController::class, 'updateProcess'])
            ->name('labtech_process-milk.update');

        // AJAX endpoint for labelling complete
        Route::post('/process-milk/{milk}/labelling-complete', [MilkController::class, 'markLabellingComplete'])
            ->name('labelling-complete');

        // AJAX endpoint for labelling in-progress
        Route::post('/process-milk/{milk}/labelling-in-progress', [MilkController::class, 'markLabellingInProgress'])
            ->name('labelling-in-progress');

        // Endpoint to fetch milk statuses for manage page polling
        Route::get('/milk-statuses', [MilkController::class, 'milkStatuses'])
            ->name('milk-statuses');

        Route::post('/labtech/milk/{milk}/save-screening-results', [MilkController::class, 'saveScreeningResults'])
            ->name('save-screening-results');

        Route::post('/process-milk/{milk}/distributing-complete', [MilkController::class, 'markDistributingComplete'])
            ->name('distributing-complete');

        // AJAX endpoint for distributing in-progress
        Route::post('/process-milk/{milk}/distributing-in-progress', [MilkController::class, 'markDistributingInProgress'])
            ->name('distributing-in-progress');

        // DELETE milk record (AJAX or normal request)
        Route::delete('/manage-milk-records/{milk}', [MilkController::class, 'destroy'])
            ->name('delete-milk');
    });  

    // ====================================================================
    // OTHER MODULES (Milk Request, Appointment, etc.)
    // ====================================================================
    Route::view('/doctor/milk-request', 'doctor.doctor_milk-request')->name('doctor.list-milk-request');
    Route::view('/nurse/milk-request', 'nurse.nurse_milk-request')->name('nurse.list-milk-request');
    Route::view('/doctor/milk-request/create', 'doctor.doctor_milk-request-form')->name('doctor.milk-request-form');
    Route::view('/nurse/allocate-milk', 'nurse.nurse_allocate-milk')->name('nurse.allocate-milk');
    Route::view('/nurse/milk-request-list', 'nurse.nurse_milk-request-list')->name('nurse.milk-request-list');
    Route::view('/nurse/set-infant-weight', 'nurse.nurse_set-infant-weight')->name('nurse.set-infant-weight');
    Route::view('/parent/my-infant-request', 'parent.parent_my-infant-request')->name('parent.my-infant-request');
    Route::view('/hmmc/list-of-infants', 'hmmc.hmmc_list-of-infants')->name('hmmc.list-of-infants');
    Route::view('/shariah/infant-request', 'shariah.shariah_infant-request')->name('shariah.infant-request');

    // Milk Records Views
    Route::view('/hmmc/manage-milk-records', 'hmmc.hmmc_manage-milk-records')->name('hmmc.manage-milk-records');
    Route::view('/shariah/manage-milk-records', 'shariah.shariah_manage-milk-records')->name('shariah.manage-milk-records');
    Route::view('/nurse/manage-milk-records', 'nurse.nurse_manage-milk-records')->name('nurse.manage-milk-records');
    Route::view('/shariah/view-milk-processing', 'shariah.shariah_view-milk-processing')->name('shariah.view-milk-processing');
    Route::view('/nurse/view-milk-processing', 'nurse.nurse_view-milk-processing')->name('nurse.view-milk-processing');

    // Appointment Module
    Route::view('/donor/appointments', 'donor.donor_appointments')->name('donor.appointments');
    Route::view('/donor/appointment-form', 'donor.donor_appointment-form')->name('donor.appointment-form');
    Route::view('/donor/confirm-appointment', 'donor.donor_confirm-appointment')->name('donor.confirm-appointment');
    Route::view('/doctor/list-of-donor-appointments', 'doctor.doctor_list-of-donor-appointments')->name('doctor.list-of-donor-appointments');
    Route::view('/donor/my-appointments', 'donor.donor_my-appointments')->name('donor.my-appointments');
    Route::view('/nurse/donor-appointment-record', 'nurse.nurse_donor-appointment-record')->name('nurse.donor-appointment-record');
    Route::view('/donor/pumping-kit-form', 'donor.donor_pumping-kit-form')->name('donor.pumping-kit-form');
    Route::view('/donor/confirm-pumping-kit-form', 'donor.donor_confirm-pumping-kit-form')->name('donor.confirm-pumping-kit-form');
    Route::view('/doctor/donor-candidate-list', 'doctor.doctor_donor-candidates')->name('doctor.donor-candidate-list');
    Route::view('/nurse/donor-candidate-list', 'nurse.nurse_donor-candidate-list')->name('nurse.donor-candidate-list');
});

// ====================================================================
// TEST ROUTE (safe to keep)
// ====================================================================

Route::get('/test-email', function () {
    try {
        Mail::send('emails.hmmc_donor-credential', [
            'fullname'  => 'Test Donor',
            'username'  => 'donor123',
            'password'  => 'secret123',
            'loginUrl'  => route('login'),
        ], function ($message) {
            $message->to('ariffnorjihan@gmail.com')
                    ->subject('Test Email from HMMC – Your Login Credentials');
        });

        return "<h2 style='color:green'>Email sent successfully!</h2>";
    } catch (Exception $e) {
        return "<h2 style='color:red'>Email failed:</h2><pre>" . $e->getMessage() . "</pre>";
    }
})->name('test.email');