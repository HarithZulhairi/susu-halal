<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;


Route::get('/reset-password-test', function () {
    return app(\App\Http\Controllers\Auth\NewPasswordController::class)
        ->create(request()->merge(['token' => 'testtoken']));
});

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// User Management Module

Route::get('/hmmc/dashboard', function () {
    return view('hmmc.hmmc_dashboard');
})->name('hmmc.dashboard');

Route::resource('users', UserController::class);
// Create new user form
Route::get('/hmmc/create-new-user/{role}', [UserController::class, 'create'])->name('hmmc.create-new-user');
Route::post('/hmmc/users/store', [UserController::class, 'store'])->name('hmmc.users.store');

Route::get('/donor/dashboard', function () {
    return view('donor.donor_dashboard');
})->name('donor.dashboard');

Route::get('/hmmc/manage-users', function () {
    return view('hmmc.hmmc_manage-users');
})->name('hmmc.manage-users');

Route::get('/donor/profile', function () {
    return view('donor.donor_profile');
})->name('donor.profile');

Route::get('/donor/edit-profile', function () {
    return view('donor.donor_edit-profile');
})->name('donor.edit-profile');

Route::get('/doctor/profile', function () {
    return view('doctor.doctor_profile');
})->name('doctor.profile');

Route::get('/doctor/edit-profile', function () {
    return view('doctor.doctor_edit-profile');
})->name('doctor.edit-profile');

Route::get('/doctor/dashboard', function () {
    return view('doctor.doctor_dashboard');
})->name('doctor.dashboard');

Route::get('/nurse/dashboard', function () {
    return view('nurse.nurse_dashboard');
})->name('nurse.dashboard');

Route::get('/nurse/profile', function () {
    return view('nurse.nurse_profile');
})->name('nurse.profile');

Route::get('/nurse/edit-profile', function () {
    return view('nurse.nurse_edit-profile');
})->name('nurse.edit-profile');

Route::get('/labtech/dashboard', function () {
    return view('labtech.labtech_dashboard');
})->name('labtech.dashboard');

Route::get('/labtech/profile', function () {
    return view('labtech.labtech_profile');
})->name('labtech.profile');

Route::get('/labtech/edit-profile', function () {
    return view('labtech.labtech_edit-profile');
})->name('labtech.edit-profile');

Route::get('/shariah/dashboard', function () {
    return view('shariah.shariah_dashboard');
})->name('shariah.dashboard');

Route::get('/shariah/profile', function () {
    return view('shariah.shariah_profile');
})->name('shariah.profile');

Route::get('/shariah/edit-profile', function () {
    return view('shariah.shariah_edit-profile');
})->name('shariah.edit-profile');

Route::get('/parent/dashboard', function () {
    return view('parent.parent_dashboard');
})->name('parent.dashboard');

Route::get('/parent/profile', function () {
    return view('parent.parent_profile');
})->name('parent.profile');

Route::get('/parent/edit-profile', function () {
    return view('parent.parent_edit-profile');
})->name('parent.edit-profile');

Route::get('/hmmc/create-new-user', function () {
    return view('hmmc.hmmc_create-new-user');
})->name('hmmc.create-new-user');


// Milk Request Module

Route::get('/doctor/milk-request', function () {
    return view('doctor.doctor_milk-request');
})->name('doctor.list-milk-request');

Route::get('/nurse/milk-request', function () {
    return view('nurse.nurse_milk-request');
})->name('nurse.list-milk-request');

// Route::get('/doctor/test', function () {
//     return view('doctor.doctor_test');
// });

// Route::get('/doctor/prescribe-milk', function () {
//     return view('doctor.doctor_prescribe-milk');
// });

Route::get('/doctor/milk-request/create', function () {
    return view('doctor.doctor_milk-request-form');
})->name('doctor.milk-request-form');

Route::get('/nurse/allocate-milk', function () {
    return view('nurse.nurse_allocate-milk');
})->name('nurse.allocate-milk');

Route::get('/nurse/milk-request-list', function () {
    return view('nurse.nurse_milk-request-list');
})->name('nurse.milk-request-list');

Route::get('/nurse/set-infant-weight', function () {
    return view('nurse.nurse_set-infant-weight');
})->name('nurse.set-infant-weight');

Route::get('/parent/my-infant-request', function () {
    return view('parent.parent_my-infant-request');
})->name('parent.my-infant-request');

Route::get('/hmmc/list-of-infants', function () {
    return view('hmmc.hmmc_list-of-infants');
})->name('hmmc.list-of-infants');

Route::get('/shariah/infant-request', function () {
    return view('shariah.shariah_infant-request');
})->name('shariah.infant-request');

// Milk Prcoessing and Record Module

Route::get('/nurse/milk-process', function () {
    return view('nurse_milk-process-record');
})->name('milk.process');

Route::get('/hmmc/manage-milk-records', function () {
    return view('hmmc.hmmc_manage-milk-records');
})->name('hmmc.manage-milk-records');

Route::get('/shariah/manage-milk-records', function () {
    return view('shariah.shariah_manage-milk-records');
})->name('shariah.manage-milk-records');

Route::get('/nurse/manage-milk-records', function () {
    return view('nurse.nurse_manage-milk-records');
})->name('nurse.manage-milk-records');

Route::get('/labtech/manage-milk-records', function () {
    return view('labtech.labtech_manage-milk-records');
})->name('labtech.manage-milk-records');

Route::get('/labtech/process-milk', function () {
    return view('labtech.labtech_process-milk');
})->name('labtech.process-milk');

Route::get('/shariah/view-milk-processing', function () {
    return view('shariah.shariah_view-milk-processing');
})->name('shariah.view-milk-processing');

Route::get('/nurse/view-milk-processing', function () {
    return view('nurse.nurse_view-milk-processing');
})->name('nurse.view-milk-processing');

// Appoinment Module

Route::get('/donor/appointments', function () {
    return view('donor.donor_appointments');
})->name('donor.appointments');

Route::get('/donor/appointment-form', function () {
    return view('donor.donor_appointment-form');
})->name('donor.appointment-form');

Route::get('/donor/confirm-appointment', function () {
    return view('donor.donor_confirm-appointment');
})->name('donor.confirm-appointment');

Route::get('/doctor/list-of-donor-appointments', function () {
    return view('doctor.doctor_list-of-donor-appointments');
})->name('doctor.list-of-donor-appointments');

Route::get('/donor/my-appointments', function () {
    return view('donor.donor_my-appointments');
})->name('donor.my-appointments');

Route::get('/nurse/donor-appointment-record', function () {
    return view('nurse.nurse_donor-appointment-record');
})->name('nurse.donor-appointment-record');

Route::get('/donor/pumping-kit-form', function () {
    return view('donor.donor_pumping-kit-form');
})->name('donor.pumping-kit-form');

Route::get('/donor/confirm-pumping-kit-form', function () {
    return view('donor.donor_confirm-pumping-kit-form');
})->name('donor.confirm-pumping-kit-form');

Route::get('/doctor/donor-candidate-list', function () {
    return view('doctor.doctor_donor-candidates');
})->name('doctor.donor-candidate-list');

Route::get('/nurse/donor-candidate-list', function () {
    return view('nurse.nurse_donor-candidate-list');
})->name('nurse.donor-candidate-list');

require __DIR__.'/auth.php';