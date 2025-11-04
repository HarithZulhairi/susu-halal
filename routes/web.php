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


// Milk Request Module

Route::get('/doctor/milk-request', function () {
    return view('doctor.doctor_milk-request');
});

Route::get('/doctor/test', function () {
    return view('doctor.doctor_test');
});

Route::get('/doctor/prescribe-milk', function () {
    return view('doctor.doctor_prescribe-milk');
});



// Milk Prcoessing and Record Module

Route::get('/nurse/milk-process', function () {
    return view('milk processing and records/nurse_MilkProcess');
})->name('milk.process');

