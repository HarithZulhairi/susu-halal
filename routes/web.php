<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// User Management Module

Route::get('/hmmc/dashboard', function () {
    return view('hmmc.hmmc_dashboard');
})->name('hmmc.dashboard');

Route::get('/hmmc/manage-users', function () {
    return view('hmmc.hmmc_manage-users');
})->name('hmmc.manage-users');

Route::get('/donor/profile', function () {
    return view('donor.donor_profile');
})->name('donor.profile');


// Milk Request Module

Route::get('/doctor/milk-request', function () {
    return view('doctor.doctor_milk-request');
})->name('doctor.list-milk-request');

Route::get('/doctor/test', function () {
    return view('doctor.doctor_test');
});

Route::get('/doctor/prescribe-milk', function () {
    return view('doctor.doctor_prescribe-milk');
});

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

// Milk Prcoessing and Record Module

Route::get('/nurse/milk-process', function () {
    return view('nurse_milk-process-record');
})->name('milk.process');

