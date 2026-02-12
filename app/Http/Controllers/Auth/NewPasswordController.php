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
use App\Models\HmmcAdmin;
use App\Models\Nurse;
use App\Models\Doctor;
use App\Models\LabTech;
use App\Models\ShariahCommittee;
use App\Models\ParentModel;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        $user = User::where('email', $request->email)->first();
        $layout = $this->getRoleLayout($user);
        
        return view('auth.reset-password', [
            'request' => $request,
            'layout' => $layout
        ]);
    }

    /**
     * Get the appropriate layout based on user role
     */
    private function getRoleLayout(?User $user): string
    {
        if (!$user) {
            return 'layouts.guest';
        }

        $roleLayouts = [
            'doctor' => 'layouts.doctor',
            'admin' => 'layouts.admin',
            'nurse' => 'layouts.nurse',
            'shariah' => 'layouts.shariah',
            'donor' => 'layouts.donor',
            'parent' => 'layouts.parent',
            'labtech' => 'layouts.labtech',
        ];

        return $roleLayouts[$user->role] ?? 'layouts.app';
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
            
            // Role-based redirection
            return $this->redirectBasedOnRole($user, $status);
        }

        return back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
    }

    /**
     * Redirect user based on their role after password reset
     */
    private function redirectBasedOnRole(?User $user, string $status): RedirectResponse
    {
        if (!$user) {
            return redirect()->route('login')->with('status', __($status));
        }

        // Role-based redirection
        switch ($user->role) {
            case 'doctor':
                return redirect()->route('doctor.dashboard')->with('status', __($status));
            case 'hmmc_admin':
                return redirect()->route('hmmc.dashboard')->with('status', __($status));
            case 'nurse':
                return redirect()->route('nurse.dashboard')->with('status', __($status));
            case 'donor':
                return redirect()->route('donor.dashboard')->with('status', __($status));
            case 'parent':
                return redirect()->route('parent.dashboard')->with('status', __($status));
            case 'labtech':
                return redirect()->route('labtech.dashboard')->with('status', __($status));
            case 'shariah_advisor':
                return redirect()->route('shariah.dashboard')->with('status', __($status));
            default:
                return redirect()->route('dashboard')->with('status', __($status));
        }
    }


    /**
     * Display the first-time password reset view for donors.
     */
    public function createFirstTime(Request $request): View
    {
        return view('auth.reset-password-firsttime', [
            'nric' => session('user_nric') ?? $request->old('nric'),
            'role' => session('auth_role')
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
            'role' => ['required', 'string'],
        ]);

        $role = $request->role;
        $nric = $request->nric;

        // Model Map
        $modelMap = [
            'hmmc_admin'     => ['model' => HmmcAdmin::class, 'field' => 'ad_NRIC', 'pass' => 'ad_Password', 'id' => 'ad_Admin'],
            'nurse'          => ['model' => Nurse::class, 'field' => 'ns_NRIC', 'pass' => 'ns_Password', 'id' => 'ns_ID'],
            'doctor'         => ['model' => Doctor::class, 'field' => 'dr_NRIC', 'pass' => 'dr_Password', 'id' => 'dr_ID'],
            'lab_technician' => ['model' => LabTech::class, 'field' => 'lt_NRIC', 'pass' => 'lt_Password', 'id' => 'lt_ID'],
            'shariah_advisor'=> ['model' => ShariahCommittee::class, 'field' => 'sc_NRIC', 'pass' => 'sc_Password', 'id' => 'sc_ID'],
            'parent'         => ['model' => ParentModel::class, 'field' => 'pr_NRIC', 'pass' => 'pr_Password', 'id' => 'pr_ID'],
            'donor'          => ['model' => Donor::class, 'field' => 'dn_NRIC', 'pass' => 'dn_Password', 'id' => 'dn_ID'],
        ];

        if (!isset($modelMap[$role])) {
            return back()->withErrors(['role' => 'Invalid role.']);
        }

        $map = $modelMap[$role];
        $userModel = $map['model']::where($map['field'], $nric)->first();

        if (!$userModel) {
            return back()->withInput($request->only('nric'))
                        ->withErrors(['nric' => 'User not found with this NRIC.']);
        }

        // ✅ CRITICAL: Update password AND set first_login to false
        $userModel->update([
            $map['pass'] => Hash::make($request->password),
            'first_login' => 0 // This prevents future first-time login prompts
        ]);

        // Update or create user record using user's email (if applicable)
        // Some users might not have email depending on implementation, but likely yes
        $emailField = match($role) {
            'hmmc_admin' => 'ad_Email',
            'nurse' => 'ns_Email',
            'doctor' => 'dr_Email',
            'lab_technician' => 'lt_Email',
            'shariah_advisor' => 'sc_Email',
            'parent' => 'pr_Email',
            'donor' => 'dn_Email',
            default => 'email'
        };
        
        $nameField = match($role) {
            'hmmc_admin' => 'ad_Name',
            'nurse' => 'ns_Name',
            'doctor' => 'dr_Name',
            'lab_technician' => 'lt_Name',
            'shariah_advisor' => 'sc_Name',
            'parent' => 'pr_Name',
            'donor' => 'dn_FullName',
            default => 'name'
        };

        if ($userModel->$emailField) {
            $user = User::updateOrCreate(
                ['email' => $userModel->$emailField],
                [
                    'name' => $userModel->$nameField,
                    'password' => Hash::make($request->password),
                    'role' => $role,
                    'role_id' => $userModel->{$map['id']}
                ]
            );

            // Log in the user
            auth()->login($user);
            // Set session role
            session(['auth_role' => $user->role]);
        }

        // Clear first-time session data
        session()->forget(['first_time_login', 'user_nric', 'auth_role']);

        $dashboardRoute = match($role) {
            'hmmc_admin' => 'hmmc.dashboard',
            'nurse' => 'nurse.dashboard',
            'doctor' => 'doctor.dashboard',
            'lab_technician' => 'labtech.dashboard',
            'shariah_advisor' => 'shariah.dashboard',
            'parent' => 'parent.dashboard',
            'donor' => 'donor.dashboard',
            default => 'dashboard',
        };

        return redirect()->route($dashboardRoute)
                    ->with('success', 'Password set successfully! Welcome to your dashboard.');
    }
}