<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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

        $role = session('auth_role');

        // Redirect user based on role
        switch ($role) {
            case 'hmmc_admin':
                return redirect()->route('hmmc.dashboard');
            case 'nurse':
                return redirect()->route('nurse.dashboard');
            case 'doctor':
                return redirect()->route('doctor.dashboard');
            case 'lab_technician':
                return redirect()->route('labTech.dashboard');
            case 'shariah_advisor':
                return redirect()->route('advisor.dashboard');
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
