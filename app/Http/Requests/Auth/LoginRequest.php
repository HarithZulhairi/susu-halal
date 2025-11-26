<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'role'     => ['required', 'string'],
        ];
    }

    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        $username = $this->input('username');
        $password = $this->input('password');
        $role     = $this->input('role');

        // 1️⃣ Fetch user from role-specific table
        $roleUser = $this->getRoleUser($role, $username);

        if (!$roleUser) {
            throw ValidationException::withMessages([
                'username' => 'Account not found for this role.',
            ]);
        }

        // 2️⃣ Verify password
        if (!Hash::check($password, $roleUser['password'])) {
            throw ValidationException::withMessages([
                'password' => 'Invalid password.',
            ]);
        }

        // 3️⃣ First-time donor
        if ($role === 'donor' && $roleUser['first_login'] == 1) {
            session([
                'first_time_donor' => true,
                'donor_nric'       => $roleUser['nric'],
            ]);
        }

        // 4️⃣ Create/update the master user
        $authUser = User::updateOrCreate(
            [
                'role'    => $role,
                'role_id' => $roleUser['id'],
            ],
            [
                'name'      => $roleUser['name'],
                'email'     => $roleUser['email'],
                'username'  => in_array($role, ['donor', 'parent']) ? null : $username,
                'ic_number' => in_array($role, ['donor', 'parent']) ? $username : null,
                'password'  => bcrypt('dummy'),
            ]
        );

        // 5️⃣ NEW: Update user_id in the role table (if null)
        $modelMap = [
            'hmmc_admin'     => \App\Models\HmmcAdmin::class,
            'nurse'          => \App\Models\Nurse::class,
            'doctor'         => \App\Models\Doctor::class,
            'lab_technician' => \App\Models\LabTech::class,
            'shariah_advisor'=> \App\Models\ShariahCommittee::class,
            'parent'         => \App\Models\ParentModel::class,
            'donor'          => \App\Models\Donor::class,
        ];

        // Only update if model exists and user_id is missing
        if (isset($modelMap[$role])) {
            $modelMap[$role]::where($roleUser['primary_key'], $roleUser['id'])
                ->whereNull('user_id') // prevents overwriting existing link
                ->update(['user_id' => $authUser->id]);
        }

        // 6️⃣ Login to application
        Auth::login($authUser, $this->boolean('remember'));

        $this->clearRateLimiter();
    }


    private function getRoleUser($role, $username)
    {
        $modelMap = [
            'hmmc_admin'     => ['model' => HmmcAdmin::class, 'field' => 'ad_Username', 'pass' => 'ad_Password', 'name' => 'ad_Name', 'email' => 'ad_Email', 'id' => 'ad_Admin'],
            'nurse'          => ['model' => Nurse::class, 'field' => 'ns_Username', 'pass' => 'ns_Password', 'name' => 'ns_Name', 'email' => 'ns_Email', 'id' => 'ns_ID'],
            'doctor'         => ['model' => Doctor::class, 'field' => 'dr_Username', 'pass' => 'dr_Password', 'name' => 'dr_Name', 'email' => 'dr_Email', 'id' => 'dr_ID'],
            'lab_technician' => ['model' => LabTech::class, 'field' => 'lt_Username', 'pass' => 'lt_Password', 'name' => 'lt_Name', 'email' => 'lt_Email', 'id' => 'lt_ID'],
            'shariah_advisor'=> ['model' => ShariahCommittee::class, 'field' => 'sc_Username', 'pass' => 'sc_Password', 'name' => 'sc_Name', 'email' => 'sc_Email', 'id' => 'sc_ID'],
            'parent'         => ['model' => ParentModel::class, 'field' => 'pr_NRIC', 'pass' => 'pr_Password', 'name' => 'pr_Name', 'email' => 'pr_Email', 'id' => 'pr_ID'],
            'donor'          => ['model' => Donor::class, 'field' => 'dn_NRIC', 'pass' => 'dn_Password', 'name' => 'dn_FullName', 'email' => 'dn_Email', 'id' => 'dn_ID'],
        ];

        if (!isset($modelMap[$role])) {
            throw ValidationException::withMessages(['role' => 'Invalid role']);
        }

        $map = $modelMap[$role];

        $record = $map['model']::where($map['field'], $username)->first();

        if (!$record) return null;

        return [
            'id'       => $record->{$map['id']},
            'primary_key' => $map['id'],
            'name'     => $record->{$map['name']},
            'email'    => $record->{$map['email']},
            'password' => $record->{$map['pass']},
            'first_login' => $role === 'donor' ? $record->first_login : 0,
            'nric'        => $role === 'donor' ? $record->{$map['field']} : null,
        ];
    }

    private function ensureIsNotRateLimited()
    {
        // Optional: implement rate limiting if needed
    }

    private function clearRateLimiter()
    {
        // Optional: clear rate limiter
    }
}
