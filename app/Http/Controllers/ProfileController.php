<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\LabTech;
use App\Models\Doctor;
use App\Models\ShariahCommittee;
use App\Models\Nurse;
use App\Models\HmmcAdmin;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $roleConfig = [
        'hmmc_admin' => [
            'table' => 'hmmcadmin',
            'prefix' => 'ad',
            'id_field' => 'ad_Admin',
            'view_folder' => 'hmmc'
        ],
        'doctor' => [
            'table' => 'doctor',
            'prefix' => 'dr',
            'id_field' => 'dr_ID',
            'view_folder' => 'doctor'
        ],
        'nurse' => [
            'table' => 'nurse',
            'prefix' => 'ns',
            'id_field' => 'ns_ID',
            'view_folder' => 'nurse'
        ],
        'lab_technician' => [
            'table' => 'labtech',
            'prefix' => 'lt',
            'id_field' => 'lt_ID',
            'view_folder' => 'labtech'
        ],
        'shariah_advisor' => [
            'table' => 'shariahcomittee',
            'prefix' => 'sc',
            'id_field' => 'sc_ID',
            'view_folder' => 'shariah'
        ],
        'parent' => [
            'table' => 'parent',
            'prefix' => 'pr',
            'id_field' => 'pr_ID',
            'view_folder' => 'parent'
        ],
        'donor' => [
            'table' => 'donor',
            'prefix' => 'dn',
            'id_field' => 'dn_ID',
            'view_folder' => 'donor'
        ]
    ];

    /**
     * Display the user's profile
     */
    public function show()
    {
        $user = Auth::user();
        $role = session('auth_role');
        
        if (!$role || !isset($this->roleConfig[$role])) {
            abort(403, 'Invalid user role');
        }

        $config = $this->roleConfig[$role];
        $profile = $this->getUserProfile($user->role_id, $config);

        if (!$profile) {
            abort(404, 'Profile not found');
        }

        $formattedProfile = $this->formatProfile($profile, $role, $config['prefix']);
        
        return view("{$config['view_folder']}.{$config['view_folder']}_profile", [
            'profile' => $formattedProfile,
            'role' => $role
        ]);
    }

    /**
     * Show the form for editing the profile
     */
    public function edit()
    {
        $user = Auth::user();
        $role = session('auth_role');
        
        if (!$role || !isset($this->roleConfig[$role])) {
            abort(403, 'Invalid user role');
        }

        $config = $this->roleConfig[$role];
        $profile = $this->getUserProfile($user->role_id, $config);

        if (!$profile) {
            abort(404, 'Profile not found');
        }

        $formattedProfile = $this->formatProfile($profile, $role, $config['prefix']);
        
        return view("{$config['view_folder']}.{$config['view_folder']}_edit-profile", [
            'profile' => $formattedProfile,
            'role' => $role
        ]);
    }

    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $role = session('auth_role');
        
        if (!$role || !isset($this->roleConfig[$role])) {
            abort(403, 'Invalid user role');
        }

        $config = $this->roleConfig[$role];
        $prefix = $config['prefix'];

        // Base validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ];

        // Role-specific validation
        if (in_array($role, ['doctor', 'nurse', 'lab_technician', 'shariah_advisor'])) {
            $rules = array_merge($rules, [
                'qualification' => 'nullable|string|max:255',
                'certification' => 'nullable|string|max:255',
                'institution' => 'nullable|string|max:255',
                'specialization' => 'nullable|string|max:255',
                'experience' => 'nullable|integer|min:0',
            ]);
        }

        if ($role === 'parent') {
            $rules = array_merge($rules, [
                'baby_name' => 'nullable|string|max:255',
                'baby_dob' => 'nullable|date',
                'baby_gender' => 'nullable|in:Male,Female',
                'baby_birth_weight' => 'nullable|numeric',
                'baby_current_weight' => 'nullable|numeric',
            ]);
        }

        if ($role === 'donor') {
            $rules = array_merge($rules, [
                'dob' => 'nullable|date',
                'infection_risk' => 'nullable|string|max:500',
                'medication' => 'nullable|string|max:500',
                'recent_illness' => 'nullable|string|max:500',
                'tobacco_alcohol' => 'nullable|boolean',
                'dietary_alerts' => 'nullable|string|max:500',
            ]);
        }

        // Password change validation (optional)
        if ($request->filled('current_password')) {
            $rules['current_password'] = 'required|string';
            $rules['new_password'] = 'required|string|min:6|confirmed';
        }

        $validated = $request->validate($rules);

        try {
            // Prepare update data based on role
            $updateData = $this->prepareUpdateData($validated, $role, $prefix);

            // Check current password if changing password
            if ($request->filled('current_password')) {
                $currentUser = DB::table($config['table'])
                    ->where($config['id_field'], $user->role_id)
                    ->first();

                if (!Hash::check($request->current_password, $currentUser->{"{$prefix}_Password"})) {
                    return back()->withErrors(['current_password' => 'Current password is incorrect']);
                }

                $updateData["{$prefix}_Password"] = Hash::make($request->new_password);
            }

            // Update the profile
            DB::table($config['table'])
                ->where($config['id_field'], $user->role_id)
                ->update($updateData);

            // Update users table
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'updated_at' => now()
                ]);

            return redirect()
                ->route('profile.show')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    /**
     * Get user profile from role-specific table
     */
    private function getUserProfile($roleId, $config)
    {
        return DB::table($config['table'])
            ->where($config['id_field'], $roleId)
            ->first();
    }

    /**
     * Format profile data for display
     */
    private function formatProfile($profile, $role, $prefix)
    {
        $formatted = [
            'id' => $profile->{$prefix === 'ad' ? "{$prefix}_Admin" : "{$prefix}_ID"},
            'nric' => $profile->{"{$prefix}_NRIC"} ?? null,
            'username' => $profile->{"{$prefix}_Username"} ?? null,
            'email' => $profile->{"{$prefix}_Email"} ?? null,
            'contact' => $profile->{"{$prefix}_Contact"} ?? null,
            'address' => $profile->{"{$prefix}_Address"} ?? null,
            'created_at' => $profile->created_at ?? null,
        ];

        // Role-specific name field
        if ($role === 'donor') {
            $formatted['name'] = $profile->{"{$prefix}_FullName"} ?? null;
        } else {
            $formatted['name'] = $profile->{"{$prefix}_Name"} ?? null;
        }

        // Staff-specific fields
        if (in_array($role, ['doctor', 'nurse', 'lab_technician', 'shariah_advisor', 'hmmc_admin'])) {
            $formatted['qualification'] = $profile->{"{$prefix}_Qualification"} ?? null;
            $formatted['certification'] = $profile->{"{$prefix}_Certification"} ?? null;
            $formatted['institution'] = $profile->{"{$prefix}_Institution"} ?? null;
            $formatted['specialization'] = $profile->{"{$prefix}_Specialization"} ?? null;
            $formatted['experience'] = $profile->{"{$prefix}_YearsOfExperience"} ?? 0;
        }

        // Parent-specific fields
        if ($role === 'parent') {
            $formatted['baby_name'] = $profile->pr_BabyName ?? null;
            $formatted['baby_dob'] = $profile->pr_BabyDOB ?? null;
            $formatted['baby_gender'] = $profile->pr_BabyGender ?? null;
            $formatted['baby_birth_weight'] = $profile->pr_BabyBirthWeight ?? null;
            $formatted['baby_current_weight'] = $profile->pr_BabyCurrentWeight ?? null;
        }

        // Donor-specific fields
        if ($role === 'donor') {
            $formatted['dob'] = $profile->dn_DOB ?? null;
            $formatted['infection_risk'] = $profile->dn_InfectionDeseaseRisk ?? null;
            $formatted['medication'] = $profile->dn_Medication ?? null;
            $formatted['recent_illness'] = $profile->dn_RecentIllness ?? null;
            $formatted['tobacco_alcohol'] = $profile->dn_TobaccoAlcohol ?? false;
            $formatted['dietary_alerts'] = $profile->dn_DietaryAlerts ?? null;
        }

        // Admin-specific fields
        if ($role === 'hmmc_admin') {
            $formatted['gender'] = $profile->ad_Gender ?? null;
        }

        return (object) $formatted;
    }

    /**
     * Prepare update data based on role
     */
    private function prepareUpdateData($validated, $role, $prefix)
    {
        $updateData = [
            "{$prefix}_Email" => $validated['email'],
            "{$prefix}_Contact" => $validated['contact'] ?? null,
            "{$prefix}_Address" => $validated['address'] ?? null,
            'updated_at' => now()
        ];

        // Name field (different for donor)
        if ($role === 'donor') {
            $updateData["{$prefix}_FullName"] = $validated['name'];
        } else {
            $updateData["{$prefix}_Name"] = $validated['name'];
        }

        // Staff fields
        if (in_array($role, ['doctor', 'nurse', 'lab_technician', 'shariah_advisor'])) {
            $updateData["{$prefix}_Qualification"] = $validated['qualification'] ?? null;
            $updateData["{$prefix}_Certification"] = $validated['certification'] ?? null;
            $updateData["{$prefix}_Institution"] = $validated['institution'] ?? null;
            $updateData["{$prefix}_Specialization"] = $validated['specialization'] ?? null;
            $updateData["{$prefix}_YearsOfExperience"] = $validated['experience'] ?? 0;
        }

        // Parent fields
        if ($role === 'parent') {
            $updateData['pr_BabyName'] = $validated['baby_name'] ?? null;
            $updateData['pr_BabyDOB'] = $validated['baby_dob'] ?? null;
            $updateData['pr_BabyGender'] = $validated['baby_gender'] ?? null;
            $updateData['pr_BabyBirthWeight'] = $validated['baby_birth_weight'] ?? null;
            $updateData['pr_BabyCurrentWeight'] = $validated['baby_current_weight'] ?? null;
        }

        // Donor fields
        if ($role === 'donor') {
            $updateData['dn_DOB'] = $validated['dob'] ?? null;
            $updateData['dn_InfectionDeseaseRisk'] = $validated['infection_risk'] ?? null;
            $updateData['dn_Medication'] = $validated['medication'] ?? null;
            $updateData['dn_RecentIllness'] = $validated['recent_illness'] ?? null;
            $updateData['dn_TobaccoAlcohol'] = $validated['tobacco_alcohol'] ?? false;
            $updateData['dn_DietaryAlerts'] = $validated['dietary_alerts'] ?? null;
        }

        return $updateData;
    }

    /**
     * Delete/deactivate account
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        $user = Auth::user();
        $role = session('auth_role');
        
        if (!$role || !isset($this->roleConfig[$role])) {
            abort(403, 'Invalid user role');
        }

        $config = $this->roleConfig[$role];
        $prefix = $config['prefix'];

        // Verify password
        $currentUser = DB::table($config['table'])
            ->where($config['id_field'], $user->role_id)
            ->first();

        if (!Hash::check($request->password, $currentUser->{"{$prefix}_Password"})) {
            return back()->withErrors(['password' => 'Password is incorrect']);
        }

        try {
            // Delete from role-specific table
            DB::table($config['table'])
                ->where($config['id_field'], $user->role_id)
                ->delete();

            // Delete from users table
            DB::table('users')->where('id', $user->id)->delete();

            // Logout
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')
                ->with('success', 'Your account has been deleted successfully.');

        } catch (\Exception $e) {
            \Log::error('Account deletion error: ' . $e->getMessage());
            return back()->with('error', 'Error deleting account: ' . $e->getMessage());
        }
    }
}