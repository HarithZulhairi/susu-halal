<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Donor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{

    /**
     * Handle an incoming authentication request.
     */
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate(); // LoginRequest handles role logic

        if (session('first_time_donor')) {
            return redirect()->route('password.first-time')
                ->with('nric', session('donor_nric'));
        }

        $request->session()->regenerate();

        $user = Auth::user();
        $role = $user->role;

        // Redirect by role
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

    public function destroy()
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }
}