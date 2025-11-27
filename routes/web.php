<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetOTPController;
use App\Http\Controllers\MilkController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Auth\DonorScreeningController;
use App\Http\Controllers\DonorAppointmentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

// Load Laravel Breeze/Fortify auth routes (login, logout, password reset, etc.)
// Only guests can access login/register
// Only guests can access login/register
// Guest routes (login/register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

});
    // Forgot Password + OTP
    Route::get('/forgot-password', [PasswordResetOTPController::class, 'showRequestForm'])->name('password.request');
    Route::post('/forgot-password/send-otp', [PasswordResetOTPController::class, 'sendOTP'])->name('password.send.otp');
    Route::post('/forgot-password/verify-otp', [PasswordResetOTPController::class, 'verifyOTP'])->name('password.verify.otp');
    Route::get('/forgot-password/reset/{user_table}/{user_id}', [PasswordResetOTPController::class, 'showResetForm'])->name('password.reset.firsttime');
    Route::post('/forgot-password/reset/{user_table}/{user_id}', [PasswordResetOTPController::class, 'resetPassword'])->name('password.reset.submit');


    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register-donor', [RegisteredUserController::class, 'store'])->name('register.donor.store');

// home page
Route::get('/', function () {
    if(auth()->check()){
        $role = auth()->user()->role;
        return match($role) {
            'hmmc_admin' => redirect()->route('hmmc.dashboard'),
            'nurse' => redirect()->route('nurse.dashboard'),
            'doctor' => redirect()->route('doctor.dashboard'),
            'lab_technician' => redirect()->route('labtech.dashboard'),
            'shariah_advisor' => redirect()->route('shariah.dashboard'),
            'parent' => redirect()->route('parent.dashboard'),
            'donor' => redirect()->route('donor.dashboard'),
            default => redirect('/'),
        };
    }
    return view('welcome');
})->name('home');

// Include the default auth routes provided by Laravel Breeze/Fortify
require __DIR__.'/auth.php';

// ====================================================================
// CUSTOM GUEST ROUTES
// ====================================================================



    // Custom: First-time password setup for new donors
    Route::get('/first-time-password', [NewPasswordController::class, 'createFirstTime'])
        ->name('password.first-time');
    Route::post('/first-time-password', [NewPasswordController::class, 'storeFirstTime'])
        ->name('password.set.first-time');


// ====================================================================
// AUTHENTICATED ROUTES
// ====================================================================

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard routes
    Route::get('/donor/dashboard', [DashboardController::class, 'donor'])->name('donor.dashboard');
    Route::get('/labtech/dashboard', [DashboardController::class, 'labtech'])->name('labtech.dashboard');
    Route::get('/doctor/dashboard', [DashboardController::class, 'doctor'])->name('doctor.dashboard');
    Route::get('/nurse/dashboard', [DashboardController::class, 'nurse'])->name('nurse.dashboard');
    Route::get('/shariah/dashboard', [DashboardController::class, 'shariah'])->name('shariah.dashboard');
    Route::get('/parent/dashboard', [DashboardController::class, 'parent'])->name('parent.dashboard');
    Route::get('/hmmc/dashboard', [DashboardController::class, 'hmmc'])->name('hmmc.dashboard');
});


// In your routes file
Route::middleware(['auth'])->group(function () {
    Route::get('/hmmc/manage-users', [UserController::class, 'index'])->name('hmmc.manage-users');
    Route::get('/hmmc/create-new-user/{role}', [UserController::class, 'create'])->name('hmmc.create-new-user');
    Route::post('/hmmc/store-new-user', [UserController::class, 'store'])->name('hmmc.store-user');
    
    // User CRUD routes
    Route::get('/hmmc/users/{role}/{id}', [UserController::class, 'show'])->name('hmmc.users.show');
    Route::get('/hmmc/users/{role}/{id}/edit', [UserController::class, 'edit'])->name('hmmc.users.edit');
    Route::put('/hmmc/users/{role}/{id}', [UserController::class, 'update'])->name('hmmc.users.update');
    Route::delete('/hmmc/users/{role}/{id}', [UserController::class, 'destroy'])->name('hmmc.users.destroy');
    
    // Credential sending route
    Route::post('/hmmc/send-credentials', [UserController::class, 'sendCredentials'])
        ->name('hmmc.send-credentials')
        ->middleware(['auth']); // Only auth middleware
});

Route::post('/hmmc/validate-user-field', [UserController::class, 'validateField'])
    ->name('hmmc.validate-user-field')
    ->middleware('auth');


Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Extra Profile Views
    Route::view('/donor/edit-profile', 'donor.donor_edit-profile')->name('donor.edit-profile');
    Route::view('/doctor/edit-profile', 'doctor.doctor_edit-profile')->name('doctor.edit-profile');
    Route::view('/hmmc/edit-profile', 'hmmc.hmmc_edit-profile')->name('hmmc.hmmc_edit-profile');
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
        Route::get('/manage-milk-records', [MilkController::class, 'viewMilkLabtech'])
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
    // Route::view('/doctor/milk-request', 'doctor.doctor_milk-request')->name('doctor.list-milk-request');
    Route::get('/doctor/milk-request', [RequestController::class, 'viewRequestDoctor'])->name('doctor.doctor_milk-request');
    
    Route::view('/nurse/milk-request', 'nurse.nurse_milk-request')->name('nurse.list-milk-request');

    // Route::view('/doctor/milk-request/create', 'doctor.doctor_milk-request-form')->name('doctor.milk-request-form');
    Route::get('/doctor/milk-request/create', [RequestController::class, 'create'])->name('doctor.doctor_milk-request-form');
    Route::post('/doctor/milk-request/create/store', [RequestController::class, 'store'])->name('doctor.doctor_milk-request-store');
    Route::delete('/doctor/milk-request/{id}/delete', [RequestController::class, 'delete'])->name('doctor.milk-request-delete');
    

    Route::view('/nurse/allocate-milk', 'nurse.nurse_allocate-milk')->name('nurse.allocate-milk');
    Route::post('/nurse/allocate/delete', [RequestController::class, 'deleteAllocation'])->name('nurse.allocate.delete');

    // Route::view('/nurse/milk-request-list', 'nurse.nurse_milk-request-list')->name('nurse.milk-request-list');
    Route::get('/nurse/milk-request-list', [RequestController::class, 'viewRequestNurse'])->name('nurse.nurse_milk-request-list');
    Route::post('/nurse/milk-request-list/store', [RequestController::class, 'allocateMilk'])->name('nurse.allocate.milk');

    // Route::view('/nurse/set-infant-weight', 'nurse.nurse_set-infant-weight')->name('nurse.set-infant-weight');
    Route::get('/nurse/set-infant-weight', [RequestController::class, 'setInfantWeightNurse'])->name('nurse.nurse_set-infant-weight');
    Route::post('/nurse/set-infant-weight/update', [RequestController::class, 'updateInfantWeightNurse'])->name('nurse.nurse_infant-weight.update');

    Route::view('/parent/my-infant-request', 'parent.parent_my-infant-request')->name('parent.my-infant-request');

    // Route::view('/hmmc/list-of-infants', 'hmmc.hmmc_list-of-infants')->name('hmmc.list-of-infants');
    Route::get('/hmmc/milk-request', [RequestController::class, 'viewRequestHMMC'])->name('hmmc.hmmc_milk-request');
    Route::get('/hmmc/list-of-infants', [RequestController::class, 'viewInfantWeightHMMC'])->name('hmmc.hmmc_list-of-infants');
    Route::post('/hmmc/list-of-infants/update', [RequestController::class, 'updateInfantWeightHMMC'])->name('hmmc.hmmc_list-of-infants.update');
    
    Route::view('/shariah/infant-request', 'shariah.shariah_infant-request')->name('shariah.infant-request');

    // Milk Records Views
    Route::get('/hmmc/manage-milk-records', [MilkController::class, 'viewMilkHMMC'])->name('hmmc.hmmc_manage-milk-records');
    Route::put('/hmmc/manage-milk-records/{id}', [MilkController::class, 'updateMilkRecordHMMC'])->name('hmmc.update-milk-record');
    Route::delete('/hmmc/manage-milk-records/{id}', [MilkController::class, 'deleteMilkRecordHMMC'])->name('hmmc.delete-milk-record');

    Route::get('/shariah/manage-milk-records', [MilkController::class, 'viewMilkShariah'])->name('shariah.shariah_manage-milk-records');

    Route::get('/nurse/manage-milk-records', [MilkController::class, 'viewMilkNurse'])->name('nurse.manage-milk-records');
    Route::get('/nurse/milk-statuses', [MilkController::class, 'milkStatuses'])->name('nurse.milk-statuses');

    Route::get('/shariah/milk-statuses', [MilkController::class, 'milkStatuses'])->name('shariah.milk-statuses');

    // Route::view('/shariah/view-milk-processing', 'shariah.shariah_view-milk-processing')->name('shariah.shariah_view-milk-processing');
    Route::get('/shariah/view-milk-processing/{id}', [MilkController::class, 'viewMilkProcessingShariah'])->name('shariah.shariah_view-milk-processing');
    Route::post('/shariah/update-decision', [MilkController::class, 'updateDecision'])->name('shariah.update-decision');
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

//Controller
Route::post('/donor/milk/store', [DonorAppointmentController::class, 'storeMilkAppointment'])
    ->name('donor.store-milk-appointment');

Route::get('/donor/appointments', [DonorAppointmentController::class, 'showAppointment'])
    ->name('donor.appointments');

Route::put('/donor/appointments/update/milk/{id}', [DonorAppointmentController::class, 'updateMilkAppointment'])
    ->name('donor.update-milk');

Route::put('/donor/appointments/update/pk/{id}', [DonorAppointmentController::class, 'updatePumpingKitAppointment'])
    ->name('donor.update-pumping');


Route::post('/donor/pk/store', [DonorAppointmentController::class, 'storePumpingKitAppointment'])
    ->name('donor.store-pk-appointment');

Route::put('/donor/appointments/cancel/milk/{id}', [DonorAppointmentController::class, 'cancelMilk'])
    ->name('donor.cancel-milk');

Route::put('/donor/appointments/cancel/pk/{id}', [DonorAppointmentController::class, 'cancelPumpingKit'])
    ->name('donor.cancel-pk');





//Controller
Route::post('/donor/milk/store', [DonorAppointmentController::class, 'storeMilkAppointment'])
    ->name('donor.store-milk-appointment');

Route::get('/donor/appointments', [DonorAppointmentController::class, 'showAppointment'])
    ->name('donor.appointments');

Route::put('/donor/appointments/update/milk/{id}', [DonorAppointmentController::class, 'updateMilkAppointment'])
    ->name('donor.update-milk');

Route::put('/donor/appointments/update/pk/{id}', [DonorAppointmentController::class, 'updatePumpingKitAppointment'])
    ->name('donor.update-pumping');


Route::post('/donor/pk/store', [DonorAppointmentController::class, 'storePumpingKitAppointment'])
    ->name('donor.store-pk-appointment');

Route::put('/donor/appointments/cancel/milk/{id}', [DonorAppointmentController::class, 'cancelMilk'])
    ->name('donor.cancel-milk');

Route::put('/donor/appointments/cancel/pk/{id}', [DonorAppointmentController::class, 'cancelPumpingKit'])
    ->name('donor.cancel-pk');

Route::get('/nurse/donor-appointment-record',[DonorAppointmentController::class, 'nurseViewAppointments'])
    ->name('nurse.donor-appointment-record');

Route::put('/nurse/appointments/confirm/milk/{reference}', 
    [DonorAppointmentController::class, 'nurseConfirmMilkAppointment']);

Route::put('/nurse/appointments/confirm/pk/{reference}', 
    [DonorAppointmentController::class, 'nurseConfirmPumpingKitAppointment']);









