<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $role = Auth::user()->role;

            return match ($role) {
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

        return $next($request);
    }
}

