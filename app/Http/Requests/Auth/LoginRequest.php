<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

// Import your role models
use App\Models\User;
use App\Models\HmmcAdmin;
use App\Models\Nurse;
use App\Models\Doctor;
use App\Models\LabTech;
use App\Models\ShariahCommittee;
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
                $user = Doctor::where('dr_Username', $username)->first();
                $passwordField = 'dr_Password';
                break;

            case 'lab_technician':
                $user = LabTech::where('lt_Username', $username)->first();
                $passwordField = 'lt_Password';
                break;

            case 'shariah_advisor':
                $user = ShariahCommittee::where('sc_Username', $username)->first();
                $passwordField = 'sc_Password';
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

        // Create a User model instance for Laravel's auth system
        $authUser = $this->createAuthUser($user, $role);
        
        // Properly log in the user using Laravel's Auth
        Auth::login($authUser, $this->boolean('remember'));
        
        // Store additional info in session
        session(['auth_role' => $role]);
        
        // Regenerate session to prevent fixation attacks
        request()->session()->regenerate();
    }

    /**
     * Create a User model instance for authentication
     */
    private function createAuthUser($roleUser, $role)
    {
        // Create or find a User model instance
        // This maps your role-specific user to Laravel's User model
        $email = $this->getUserEmail($roleUser, $role);
        $name = $this->getUserName($roleUser, $role);
        $userId = $this->getUserId($roleUser, $role);

        // Find or create user in users table
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => bcrypt('dummy'), // Not used for auth
                'role' => $role,
                'role_id' => $userId
            ]
        );

        return $user;
    }

    private function getUserEmail($user, $role)
    {
        switch ($role) {
            case 'hmmc_admin':
                return $user->ad_Email;
            case 'nurse':
                return $user->ns_Email;
            case 'doctor':
                return $user->dr_Email;
            case 'lab_technician':
                return $user->lt_Email;
            case 'shariah_advisor':
                return $user->sc_Email;
            case 'parent':
                return $user->pr_Email;
            case 'donor':
                return $user->dn_Email;
            default:
                return '';
        }
    }

    private function getUserName($user, $role)
    {
        switch ($role) {
            case 'hmmc_admin':
                return $user->ad_Name;
            case 'nurse':
                return $user->ns_Name;
            case 'doctor':
                return $user->dr_Name;
            case 'lab_technician':
                return $user->lt_Name;
            case 'shariah_advisor':
                return $user->sc_Name;
            case 'parent':
                return $user->pr_Name;
            case 'donor':
                return $user->dn_FullName;
            default:
                return '';
        }
    }

    private function getUserId($user, $role)
    {
        switch ($role) {
            case 'hmmc_admin':
                return $user->ad_Admin;
            case 'nurse':
                return $user->ns_ID;
            case 'doctor':
                return $user->dr_ID;
            case 'lab_technician':
                return $user->lt_ID;
            case 'shariah_advisor':
                return $user->sc_ID;
            case 'parent':
                return $user->pr_ID;
            case 'donor':
                return $user->dn_ID;
            default:
                return null;
        }
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