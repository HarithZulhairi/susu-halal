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
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Check if this is a first-time donor login (from session)
        if (session('first_time_donor')) {
            return redirect()->route('password.first-time')
                ->with('nric', session('donor_nric'))
                ->with('info', 'Welcome! Please set a permanent password for your account.');
        }

        $request->session()->regenerate();

        $role = Auth::user()->role;

        // Redirect user based on role
        switch ($role) {
            case 'hmmc_admin':
                return redirect()->route('hmmc.dashboard');
            case 'nurse':
                return redirect()->route('nurse.dashboard');
            case 'doctor':
                return redirect()->route('doctor.dashboard');
            case 'lab_technician':
                return redirect()->route('labtech.dashboard');
            case 'shariah_advisor':
                return redirect()->route('shariah.dashboard');
            case 'parent':
                return redirect()->route('parent.dashboard');
            case 'donor':
                return redirect()->route('donor.dashboard');
            default:
                abort(403, 'Unauthorized role.');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}