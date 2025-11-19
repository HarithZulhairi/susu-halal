<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Donor;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                auth()->login($user);
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            $user = User::where('email', $request->email)->first();
            if ($user && $user->role === 'donor') {
                return redirect()->route('donor.dashboard')->with('status', __($status));
            }
            return redirect()->route('login')->with('status', __($status));
        }

        return back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
    }

    /**
     * Display the first-time password reset view for donors.
     */
    public function createFirstTime(Request $request): View
    {
        return view('auth.reset-password-firsttime', [
            'nric' => session('donor_nric') ?? $request->old('nric')
        ]);
    }

        /**
     * Handle first-time password reset for donors.
     */
    public function storeFirstTime(Request $request): RedirectResponse
    {
        $request->validate([
            'nric' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Find the donor by NRIC
        $donor = Donor::where('dn_NRIC', $request->nric)->first();

        if (!$donor) {
            return back()->withInput($request->only('nric'))
                        ->withErrors(['nric' => 'Donor not found with this NRIC.']);
        }

        // ✅ CRITICAL: Update password AND set first_login to false
        $donor->update([
            'dn_Password' => Hash::make($request->password),
            'first_login' => 0 // This prevents future first-time login prompts
        ]);

        // Update or create user record using donor's email
        $user = User::updateOrCreate(
            ['email' => $donor->dn_Email],
            [
                'name' => $donor->dn_FullName,
                'password' => Hash::make($request->password),
                'role' => 'donor',
                'role_id' => $donor->dn_ID
            ]
        );

        // Log in the user
        auth()->login($user);

        // Clear first-time session data
        session()->forget(['first_time_donor', 'donor_nric', 'donor_email', 'donor_id', 'donor_name']);

        return redirect()->route('donor.dashboard')
                    ->with('success', 'Password set successfully! Welcome to your dashboard.');
    }
}