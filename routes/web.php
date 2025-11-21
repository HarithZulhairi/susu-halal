<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\MilkController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/reset-password-test', function () {
    return app(\App\Http\Controllers\Auth\NewPasswordController::class)
        ->create(request()->merge(['token' => 'testtoken']));
});

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
// Route::get('/login', [LoginController::class, 'create'])->name('login');
// Route::post('/login', [LoginController::class, 'store']);
// Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// Role-based dashboard routes (protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/hmmc/dashboard', function () {
        return view('hmmc.dashboard');
    })->name('hmmc.dashboard');

    Route::get('/nurse/dashboard', function () {
        return view('nurse.dashboard');
    })->name('nurse.dashboard');

    Route::get('/doctor/dashboard', function () {
        return view('doctor.dashboard');
    })->name('doctor.dashboard');

    Route::get('/labtech/dashboard', function () {
        return view('labtech.dashboard');
    })->name('labtech.dashboard');

    Route::get('/shariah/dashboard', function () {
        return view('shariah.dashboard');
    })->name('shariah.dashboard');

    Route::get('/parent/dashboard', function () {
        return view('parent.dashboard');
    })->name('parent.dashboard');

    Route::get('/donor/dashboard', function () {
        return view('donor.dashboard');
    })->name('donor.dashboard');
});


// User Management Module

Route::get('/hmmc/dashboard', function () {
    return view('hmmc.hmmc_dashboard');
})->name('hmmc.dashboard');

Route::resource('users', UserController::class);
// Create new user form
Route::middleware(['auth'])->group(function () {
    Route::get('/hmmc/manage-users', [UserController::class, 'index'])->name('hmmc.manage-users');
    Route::get('/hmmc/create-new-user/{role}', [UserController::class, 'create'])->name('hmmc.create-new-user');
    Route::post('/hmmc/store-new-user', [UserController::class, 'store'])->name('hmmc.store-user');
    
    // User CRUD routes - make sure these come after more specific routes
    Route::get('/hmmc/users/{role}/{id}', [UserController::class, 'show'])->name('hmmc.users.show');
    Route::get('/hmmc/users/{role}/{id}/edit', [UserController::class, 'edit'])->name('hmmc.users.edit');
    Route::put('/hmmc/users/{role}/{id}', [UserController::class, 'update'])->name('hmmc.users.update');
    Route::delete('/hmmc/users/{role}/{id}', [UserController::class, 'destroy'])->name('hmmc.users.destroy');
});

// Admin User Management Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/{role}/{id}', [AdminUserController::class, 'show'])->name('show');
        Route::get('/{role}/{id}/edit', [AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{role}/{id}', [AdminUserController::class, 'update'])->name('update');
        Route::delete('/{role}/{id}', [AdminUserController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/donor/dashboard', function () {
    return view('donor.donor_dashboard');
})->name('donor.dashboard');

Route::get('/donor/edit-profile', function () {
    return view('donor.donor_edit-profile');
})->name('donor.edit-profile');

Route::get('/doctor/edit-profile', function () {
    return view('doctor.doctor_edit-profile');
})->name('doctor.edit-profile');

Route::get('/doctor/dashboard', function () {
    return view('doctor.doctor_dashboard');
})->name('doctor.dashboard');

Route::get('/nurse/dashboard', function () {
    return view('nurse.nurse_dashboard');
})->name('nurse.dashboard');

Route::get('/labtech/dashboard', function () {
    return view('labtech.labtech_dashboard');
})->name('labtech.dashboard');

Route::get('/shariah/dashboard', function () {
    return view('shariah.shariah_dashboard');
})->name('shariah.dashboard');

Route::get('/shariah/profile', function () {
    return view('shariah.shariah_profile');
})->name('shariah.profile');

Route::get('/parent/dashboard', function () {
    return view('parent.parent_dashboard');
})->name('parent.dashboard');

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

// Route::get('/labtech/manage-milk-records', function () {
//     return view('labtech.labtech_manage-milk-records');
// })->name('labtech.manage-milk-records');

Route::get('/labtech/manage-milk-records', [MilkController::class, 'showDonorinForm'])->name('labtech.labtech_manage-milk-records');
Route::post('/labtech/manage-milk-records/store', [MilkController::class, 'storeMilkRecord'])->name('labtech.labtech_store-manage-milk-records');

Route::get('/labtech/process-milk', function () {
    return view('labtech.labtech_process-milk');
})->name('labtech.labtech_process-milk');

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

