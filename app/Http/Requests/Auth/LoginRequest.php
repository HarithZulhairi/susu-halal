<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

// Import your role models
use App\Models\User;
use App\Models\HmmcAdmin;
use App\Models\Nurse;
use App\Models\Doctor;
use App\Models\LabTech;
use App\Models\ShariahAdvisor;
use App\Models\ParentModel;
use App\Models\Donor;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $role = $this->input('role');
        $username = $this->input('username');
        $password = $this->input('password');

        $user = null;

        switch ($role) {
            case 'hmmc_admin':
                $user = HmmcAdmin::where('ad_Username', $username)->first();
                $passwordField = 'ad_Password';
                break;

            case 'nurse':
                $user = Nurse::where('ns_Username', $username)->first();
                $passwordField = 'ns_Password';
                break;

            case 'doctor':
                $user = Clinician::where('cn_Username', $username)->first();
                $passwordField = 'cn_Password';
                break;

            case 'lab_technician':
                $user = LabTech::where('lt_Username', $username)->first();
                $passwordField = 'lt_Password';
                break;

            case 'shariah_advisor':
                $user = ShariahAdvisor::where('sa_Username', $username)->first();
                $passwordField = 'sa_Password';
                break;

            case 'parent':
                $user = ParentModel::where('pr_Email', $username)->first();
                $passwordField = 'pr_Password';
                break;

            case 'donor':
                $user = Donor::where('dn_Username', $username)->first();
                $passwordField = 'dn_Password';
                break;

            default:
                throw ValidationException::withMessages([
                    'role' => 'Invalid role selected.',
                ]);
        }

        if (! $user || ! Hash::check($password, $user->{$passwordField})) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        // Store user in session manually
        session(['auth_user' => $user, 'auth_role' => $role]);
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::lower($this->input('username')).'|'.$this->ip();
    }
}
