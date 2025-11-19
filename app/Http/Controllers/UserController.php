<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\LabTech;
use App\Models\Doctor;
use App\Models\ShariahCommittee;
use App\Models\Nurse;
use App\Models\HmmcAdmin;
use App\Models\Donor;
use App\Models\DonorToBe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $roles = [
        'parent', 'shariah', 'nurse', 'doctor', 'labtech', 'admin', 'donor'
    ];

    // Map role to table config
    private $roleConfig = [
        'parent' => ['table' => 'parent', 'prefix' => 'pr', 'id_field' => 'pr_ID'],
        'doctor' => ['table' => 'doctor', 'prefix' => 'dr', 'id_field' => 'dr_ID'],
        'nurse' => ['table' => 'nurse', 'prefix' => 'ns', 'id_field' => 'ns_ID'],
        'labtech' => ['table' => 'labtech', 'prefix' => 'lt', 'id_field' => 'lt_ID'],
        'shariah' => ['table' => 'shariahcomittee', 'prefix' => 'sc', 'id_field' => 'sc_ID'],
        'admin' => ['table' => 'hmmcadmin', 'prefix' => 'ad', 'id_field' => 'ad_Admin'],
        'donor' => ['table' => 'donor', 'prefix' => 'dn', 'id_field' => 'dn_ID']
    ];

    public function index()
    {
        // Get users from all tables first
        $parents = DB::table('parent')->get();
        $doctors = DB::table('doctor')->get();
        $nurses = DB::table('nurse')->get();
        $labtechs = DB::table('labtech')->get();
        $shariahs = DB::table('shariahcomittee')->get();
        $admins = DB::table('hmmcadmin')->get();
        
        // Use Eloquent for donors to get screening data
        $donors = Donor::with('screening')->get();
        $donorScreenings = DB::table('donor_to_be')->get()->keyBy('dn_ID');

        // Format and merge all users
        $allUsers = collect();
        $allUsers = $allUsers->merge($this->formatUsers($parents, 'parent', 'pr'));
        $allUsers = $allUsers->merge($this->formatUsers($doctors, 'doctor', 'dr'));
        $allUsers = $allUsers->merge($this->formatUsers($nurses, 'nurse', 'ns'));
        $allUsers = $allUsers->merge($this->formatUsers($labtechs, 'labtech', 'lt'));
        $allUsers = $allUsers->merge($this->formatUsers($shariahs, 'shariah', 'sc'));
        $allUsers = $allUsers->merge($this->formatUsers($admins, 'admin', 'ad'));
        $allUsers = $allUsers->merge($this->formatDonorUsers($donors, $donorScreenings));

        $totalUsers = $allUsers->count();

        return view('hmmc.hmmc_manage-users', [
            'allUsers' => $allUsers,
            'totalUsers' => $totalUsers,
            'donors' => $donors,
            'parents' => $parents,
            'doctors' => $doctors,
            'nurses' => $nurses,
            'labtechs' => $labtechs,
            'shariahs' => $shariahs,
            'admins' => $admins
        ]);
    }

    /**
     * Special formatter for donors with screening data
     */
    private function formatDonorUsers($donors, $donorScreenings = null)
    {
        return $donors->map(function ($donor) use ($donorScreenings) {
            $id = $donor->dn_ID;
            
            // Get screening data - first try from relationship, then from screenings array
            $screeningStatus = 'pending';
            $screeningRemark = null;
            
            if ($donor->screening) {
                $screeningStatus = $donor->screening->dtb_ScreeningStatus;
                $screeningRemark = $donor->screening->dtb_ScreeningRemark;
            } elseif ($donorScreenings && $donorScreenings->has($id)) {
                $screening = $donorScreenings->get($id);
                $screeningStatus = $screening->dtb_ScreeningStatus;
                $screeningRemark = $screening->dtb_ScreeningRemark;
            } else {
                // Auto-approve donors who don't have screening records
                $screeningStatus = 'passed';
                $screeningRemark = 'Auto-approved during registration';
            }

            return (object) [
                'id' => $id,
                'name' => $donor->dn_FullName ?? 'N/A',
                'email' => $donor->dn_Email ?? 'N/A',
                'username' => $donor->dn_Username ?? 'N/A',
                'contact' => $donor->dn_Contact ?? 'N/A',
                'role' => 'donor',
                'status' => 'active',
                'last_login' => $donor->last_login_at ?? null,
                'created_at' => $donor->created_at ?? now(),
                'original_id' => $id,
                'original_table' => 'donor',
                'screening_status' => $screeningStatus,
                'screening_remark' => $screeningRemark,
                'screening' => $donor->screening
            ];
        });
    }

    /**
     * Format users from different tables to consistent structure
     */
    private function formatUsers($users, $role, $prefix)
    {
        return $users->map(function ($user) use ($role, $prefix) {
            // Get ID field based on prefix
            $idField = $prefix === 'ad' ? "{$prefix}_Admin" : "{$prefix}_ID";
            
            // Special handling for donor name field
            $nameField = $role === 'donor' ? "{$prefix}_FullName" : "{$prefix}_Name";
            
            // Get field values safely
            $id = $user->$idField ?? null;
            $name = $user->$nameField ?? 'N/A';
            $email = property_exists($user, "{$prefix}_Email") ? $user->{"{$prefix}_Email"} : 'N/A';
            $username = property_exists($user, "{$prefix}_Username") ? $user->{"{$prefix}_Username"} : 'N/A';
            $contact = property_exists($user, "{$prefix}_Contact") ? $user->{"{$prefix}_Contact"} : 'N/A';
            
            // Base user data
            $userData = [
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'username' => $username,
                'contact' => $contact,
                'role' => $role,
                'status' => 'active',
                'last_login' => $user->last_login_at ?? null,
                'created_at' => $user->created_at ?? now(),
                'original_id' => $id,
                'original_table' => $role
            ];

            return (object) $userData;
        });
    }

    /**
     * Display user details (Admin view)
     */
    public function show($role, $id)
    {
        if (!isset($this->roleConfig[$role])) {
            abort(404, 'Invalid role');
        }

        $config = $this->roleConfig[$role];
        $user = DB::table($config['table'])
            ->where($config['id_field'], $id)
            ->first();

        if (!$user) {
            abort(404, 'User not found');
        }

        $formattedUser = $this->formatUserForAdmin($user, $role, $config['prefix']);

        return view('hmmc.admin_view-user', [
            'user' => $formattedUser,
            'role' => $role
        ]);
    }

    /**
     * Show edit form for specific user (Admin)
     */
    public function edit($role, $id)
    {
        if (!isset($this->roleConfig[$role])) {
            abort(404, 'Invalid role');
        }

        $config = $this->roleConfig[$role];
        $user = DB::table($config['table'])
            ->where($config['id_field'], $id)
            ->first();

        if (!$user) {
            abort(404, 'User not found');
        }

        $formattedUser = $this->formatUserForAdmin($user, $role, $config['prefix']);

        return view('hmmc.admin_edit-user', [
            'user' => $formattedUser,
            'role' => $role
        ]);
    }

    /**
     * Update specific user (Admin)
     */
    public function update(Request $request, $role, $id)
    {
        if (!isset($this->roleConfig[$role])) {
            abort(404, 'Invalid role');
        }

        $config = $this->roleConfig[$role];
        $prefix = $config['prefix'];

        // Base validation
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ];

        // Role-specific validation
        if (in_array($role, ['doctor', 'nurse', 'labtech', 'shariah'])) {
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

        // Password is optional for admin edits
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6';
        }

        $validated = $request->validate($rules);

        try {
            // Prepare update data
            $updateData = $this->prepareUserUpdateData($validated, $role, $prefix);

            // Add password if provided
            if ($request->filled('password')) {
                $updateData["{$prefix}_Password"] = Hash::make($request->password);
            }

            $updateData['updated_at'] = now();

            // Update user in role-specific table
            DB::table($config['table'])
                ->where($config['id_field'], $id)
                ->update($updateData);

            // Update users table if exists
            DB::table('users')
                ->where('role', $role)
                ->where('role_id', $id)
                ->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'updated_at' => now()
                ]);

            return redirect()
                ->route('hmmc.manage-users')
                ->with('success', ucfirst($role) . ' updated successfully!');

        } catch (\Exception $e) {
            \Log::error('User update error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    /**
     * Delete user (Admin)
     */
    public function destroy($role, $id)
    {
        if (!isset($this->roleConfig[$role])) {
            return response()->json(['error' => 'Invalid role'], 404);
        }

        $config = $this->roleConfig[$role];

        try {
            // Delete from role-specific table
            DB::table($config['table'])
                ->where($config['id_field'], $id)
                ->delete();

            // Delete from users table
            DB::table('users')
                ->where('role', $role)
                ->where('role_id', $id)
                ->delete();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('User delete error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Format user data for admin views
     */
    private function formatUserForAdmin($user, $role, $prefix)
    {
        $idField = $prefix === 'ad' ? "{$prefix}_Admin" : "{$prefix}_ID";
        
        $formatted = [
            'id' => $user->$idField,
            'nric' => $user->{"{$prefix}_NRIC"} ?? null,
            'username' => $user->{"{$prefix}_Username"} ?? null,
            'email' => $user->{"{$prefix}_Email"} ?? null,
            'contact' => $user->{"{$prefix}_Contact"} ?? null,
            'address' => $user->{"{$prefix}_Address"} ?? null,
            'created_at' => $user->created_at ?? null,
        ];

        // Name field (different for donor)
        if ($role === 'donor') {
            $formatted['name'] = $user->{"{$prefix}_FullName"} ?? null;
        } else {
            $formatted['name'] = $user->{"{$prefix}_Name"} ?? null;
        }

        // Staff fields
        if (in_array($role, ['doctor', 'nurse', 'labtech', 'shariah', 'admin'])) {
            $formatted['qualification'] = $user->{"{$prefix}_Qualification"} ?? null;
            $formatted['certification'] = $user->{"{$prefix}_Certification"} ?? null;
            $formatted['institution'] = $user->{"{$prefix}_Institution"} ?? null;
            $formatted['specialization'] = $user->{"{$prefix}_Specialization"} ?? null;
            $formatted['experience'] = $user->{"{$prefix}_YearsOfExperience"} ?? 0;
        }

        // Parent fields
        if ($role === 'parent') {
            $formatted['baby_name'] = $user->pr_BabyName ?? null;
            $formatted['baby_dob'] = $user->pr_BabyDOB ?? null;
            $formatted['baby_gender'] = $user->pr_BabyGender ?? null;
            $formatted['baby_birth_weight'] = $user->pr_BabyBirthWeight ?? null;
            $formatted['baby_current_weight'] = $user->pr_BabyCurrentWeight ?? null;
        }

        // Donor fields
        if ($role === 'donor') {
            $formatted['dob'] = $user->dn_DOB ?? null;
            $formatted['infection_risk'] = $user->dn_InfectionDeseaseRisk ?? null;
            $formatted['medication'] = $user->dn_Medication ?? null;
            $formatted['recent_illness'] = $user->dn_RecentIllness ?? null;
            $formatted['tobacco_alcohol'] = $user->dn_TobaccoAlcohol ?? false;
            $formatted['dietary_alerts'] = $user->dn_DietaryAlerts ?? null;
        }

        // Admin fields
        if ($role === 'admin') {
            $formatted['gender'] = $user->ad_Gender ?? null;
        }

        return (object) $formatted;
    }

    /**
     * Prepare update data for user editing
     */
    private function prepareUserUpdateData($validated, $role, $prefix)
    {
        $updateData = [
            "{$prefix}_Email" => $validated['email'],
            "{$prefix}_Contact" => $validated['contact'] ?? null,
            "{$prefix}_Address" => $validated['address'] ?? null,
        ];

        // Name field (different for donor)
        if ($role === 'donor') {
            $updateData["{$prefix}_FullName"] = $validated['name'];
        } else {
            $updateData["{$prefix}_Name"] = $validated['name'];
        }

        // Staff fields
        if (in_array($role, ['doctor', 'nurse', 'labtech', 'shariah'])) {
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

    public function create($role)
    {
        if (!in_array($role, $this->roles)) {
            abort(404, 'Invalid role');
        }
        return view('hmmc.hmmc_create-new-user', compact('role'));
    }

    public function store(Request $request)
    {
        \Log::info('Store method called', $request->all());
        
        $role = $request->input('role');

        if (!in_array($role, $this->roles)) {
            abort(400, 'Invalid role');
        }

        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email',
                'username' => 'required|string',
                'password' => 'required|string|min:6',
                'nric'     => 'required_if:role,admin,doctor,labtech,shariah,nurse,parent,donor|string|max:20',
            ]);

            \Log::info('Validation passed');

            $password = Hash::make($request->input('password'));

            switch ($role) {
                case 'admin':
                    DB::table('hmmcadmin')->insert([
                        'ad_Name'     => $request->name,
                        'ad_NRIC'     => $request->nric,
                        'ad_Username' => $request->username,
                        'ad_Password' => $password,
                        'ad_Email'    => $request->email,
                        'ad_Contact'  => $request->contact ?? null,
                        'ad_Address'  => $request->address ?? null,
                        'ad_Gender'   => $request->gender ?? null,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                    break;

                case 'doctor':
                    DB::table('doctor')->insert([
                        'dr_Name'          => $request->name,
                        'dr_NRIC'          => $request->nric,
                        'dr_Username'      => $request->username,
                        'dr_Password'      => $password,
                        'dr_Email'         => $request->email,
                        'dr_Contact'       => $request->contact ?? null,
                        'dr_Address'       => $request->address ?? null,
                        'dr_Qualification' => $request->qualification ?? null,
                        'dr_Certification' => $request->certification ?? null,
                        'dr_Institution'   => $request->institution ?? null,
                        'dr_Specialization'=> $request->specialization ?? null,
                        'dr_YearsOfExperience' => $request->experience ?? 0,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);
                    break;

                case 'nurse':
                    DB::table('nurse')->insert([
                        'ns_Name'          => $request->name,
                        'ns_NRIC'          => $request->nric,
                        'ns_Username'      => $request->username,
                        'ns_Password'      => $password,
                        'ns_Email'         => $request->email,
                        'ns_Contact'       => $request->contact ?? null,
                        'ns_Address'       => $request->address ?? null,
                        'ns_Qualification' => $request->qualification ?? null,
                        'ns_Certification' => $request->certification ?? null,
                        'ns_Institution'   => $request->institution ?? null,
                        'ns_Specialization'=> $request->specialization ?? null,
                        'ns_YearsOfExperience' => $request->experience ?? 0,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);
                    break;

                case 'labtech':
                    DB::table('labtech')->insert([
                        'lt_Name'          => $request->name,
                        'lt_NRIC'          => $request->nric,
                        'lt_Username'      => $request->username,
                        'lt_Password'      => $password,
                        'lt_Email'         => $request->email,
                        'lt_Contact'       => $request->contact ?? null,
                        'lt_Address'       => $request->address ?? null,
                        'lt_Qualification' => $request->qualification ?? null,
                        'lt_Certification' => $request->certification ?? null,
                        'lt_Institution'   => $request->institution ?? null,
                        'lt_Specialization'=> $request->specialization ?? null,
                        'lt_YearsOfExperience' => $request->experience ?? 0,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);
                    break;

                case 'shariah':
                    DB::table('shariahcomittee')->insert([
                        'sc_Name'          => $request->name,
                        'sc_NRIC'          => $request->nric,
                        'sc_Username'      => $request->username,
                        'sc_Password'      => $password,
                        'sc_Email'         => $request->email,
                        'sc_Contact'       => $request->contact ?? null,
                        'sc_Address'       => $request->address ?? null,
                        'sc_Qualification' => $request->qualification ?? null,
                        'sc_Certification' => $request->certification ?? null,
                        'sc_Institution'   => $request->institution ?? null,
                        'sc_Specialization'=> $request->specialization ?? null,
                        'sc_YearsOfExperience' => $request->experience ?? 0,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);
                    break;

                case 'parent':
                    DB::table('parent')->insert([
                        'pr_Name'              => $request->name,
                        'pr_NRIC'              => $request->nric,
                        'pr_Username'          => $request->username,
                        'pr_Password'          => $password,
                        'pr_Email'             => $request->email,
                        'pr_Address'           => $request->address ?? null,
                        'pr_Contact'           => $request->contact ?? null,
                        'pr_BabyName'          => $request->baby_name ?? null,
                        'pr_BabyDOB'           => $request->baby_dob ?? null,
                        'pr_BabyGender'        => $request->baby_gender ?? null,
                        'pr_BabyBirthWeight'   => $request->baby_birth_weight ?? null,
                        'pr_BabyCurrentWeight' => $request->baby_current_weight ?? null,
                        'created_at'           => now(),
                        'updated_at'           => now(),
                    ]);
                    break;

                case 'donor':
                    DB::table('donor')->insert([
                        'dn_FullName'            => $request->name,
                        'dn_NRIC'               => $request->nric,
                        'dn_Username'           => $request->username,
                        'dn_Password'           => $password,
                        'dn_Email'              => $request->email,
                        'dn_Contact'            => $request->contact ?? null,
                        'dn_Address'            => $request->address ?? null,
                        'dn_DOB'                => $request->dob ?? null,
                        'dn_InfectionDeseaseRisk' => $request->infection_risk ?? null,
                        'dn_Medication'         => $request->medication ?? null,
                        'dn_RecentIllness'      => $request->recent_illness ?? null,
                        'dn_TobaccoAlcohol'     => $request->tobacco_alcohol ?? false,
                        'dn_DietaryAlerts'      => $request->dietary_alerts ?? null,
                        'created_at'            => now(),
                        'updated_at'            => now(),
                    ]);
                    break;
            }

            \Log::info('User saved successfully');

            return redirect()->route('hmmc.manage-users')
                     ->with('success', ucfirst($role) . ' created successfully!');

        } catch (\Exception $e) {
            \Log::error('Error saving user: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return back()->withInput()->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    public function sendCredentials(Request $request)
    {
        \Log::info('Send credentials request started', $request->all());

        try {
            $request->validate([
                'donor_id' => 'required|integer',
                'donor_name' => 'required|string',
                'donor_email' => 'nullable|email',
                'donor_contact' => 'nullable|string',
            ]);

            // Get donor data
            $donor = DB::table('donor')
                ->where('dn_ID', $request->donor_id)
                ->first();

            \Log::info('Donor lookup result:', ['found' => !!$donor, 'donor_id' => $request->donor_id]);

            if (!$donor) {
                \Log::error('Donor not found with ID: ' . $request->donor_id);
                return response()->json([
                    'success' => false,
                    'message' => 'Donor not found.'
                ], 404);
            }

            $username = $donor->dn_Username;
            $temporaryPassword = $this->generateTemporaryPassword();

            \Log::info('Generated temporary password for donor', [
                'donor_id' => $donor->dn_ID,
                'username' => $username
            ]);

            // Update donor password with temporary one
            DB::table('donor')
                ->where('dn_ID', $request->donor_id)
                ->update([
                    'dn_Password' => Hash::make($temporaryPassword),
                    'updated_at' => now()
                ]);

            // Update or create user record
            DB::table('users')->updateOrInsert(
                ['email' => $donor->dn_Email],
                [
                    'name' => $donor->dn_FullName,
                    'password' => Hash::make($temporaryPassword),
                    'role' => 'donor',
                    'role_id' => $donor->dn_ID,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $whatsappSent = false;
            $emailSent = false;
            $errors = [];

            // Send WhatsApp if contact provided
            if ($request->donor_contact && $request->donor_contact !== 'N/A') {
                \Log::info('Attempting WhatsApp send to: ' . $request->donor_contact);
                try {
                    $whatsappSent = $this->sendWhatsAppCredentials(
                        $request->donor_contact,
                        $username,
                        $temporaryPassword,
                        $request->donor_name
                    );
                    \Log::info('WhatsApp send result: ' . ($whatsappSent ? 'success' : 'failed'));
                } catch (\Exception $e) {
                    $errors[] = 'WhatsApp: ' . $e->getMessage();
                    \Log::error('WhatsApp send error: ' . $e->getMessage());
                }
            }

            // Send Email if email provided
            if ($request->donor_email && $request->donor_email !== 'N/A') {
                \Log::info('Attempting email send to: ' . $request->donor_email);
                try {
                    $emailSent = $this->sendEmailCredentials(
                        $request->donor_email,
                        $username,
                        $temporaryPassword,
                        $request->donor_name
                    );
                    \Log::info('Email send result: ' . ($emailSent ? 'success' : 'failed'));
                } catch (\Exception $e) {
                    $errors[] = 'Email: ' . $e->getMessage();
                    \Log::error('Email send error: ' . $e->getMessage());
                    \Log::error('Email error details: ' . $e->getTraceAsString());
                }
            }

            $message = $this->getSendResultMessage($whatsappSent, $emailSent);
            
            if (!empty($errors)) {
                $message .= ' Errors: ' . implode(', ', $errors);
            }

            \Log::info('Credential sending completed', [
                'message' => $message,
                'whatsapp_sent' => $whatsappSent,
                'email_sent' => $emailSent
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'whatsapp_sent' => $whatsappSent,
                'email_sent' => $emailSent
            ]);

        } catch (\Exception $e) {
            \Log::error('Send credentials overall error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send credentials: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Send WhatsApp credentials (using your phone number for testing)
     */
    private function sendWhatsAppCredentials($phone, $username, $password, $fullname)
    {
        try {
            $message = "🎉 *CONGRATULATIONS {$fullname}!* 🎉

    🌟 *YOUR HMMC DONOR ACCOUNT IS APPROVED!* 🌟

    We're absolutely thrilled to welcome you to our family of caring donors! Your generous decision will make a profound difference in the lives of precious babies. ❤️

    *YOUR LOGIN CREDENTIALS:*

    👤 *Username:* NRIC

    🔑 *Password:* {$password}

    🔐 *IMPORTANT:* Please change your password after first login for security

    🚀 *GET STARTED:* " . route('login') . "

    🌈 *You're about to change lives!* 🌈
    Every drop brings hope and health to families in need.

    With heartfelt gratitude,
    *HMMC Team*
    HALIMATUSSAADIA Mother's Milk Centre

    💫 Together, we're nourishing futures 💫";

            // Generate WhatsApp link
            $cleanPhone = $this->formatPhoneNumber($phone);
            $encodedMessage = urlencode($message);
            $whatsappLink = "https://wa.me/{$cleanPhone}?text={$encodedMessage}";

            Log::info("WhatsApp approval credentials link for {$fullname}: {$whatsappLink}");

            // Auto-open in development
            if (app()->environment('local')) {
                $this->openWhatsAppLink($whatsappLink);
            }

            return true;

        } catch (\Exception $e) {
            Log::error('WhatsApp approval send failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email credentials using your existing hmmc_donor-credential view
     */
    private function sendEmailCredentials($email, $username, $password, $fullname)
    {
        try {
            Mail::send('hmmc.hmmc_donor-credential', [
                'fullname' => $fullname,
                'username' => $username,
                'password' => $password,
                'loginUrl' => route('login')
            ], function ($message) use ($email, $fullname) {
                $message->to($email)
                        ->subject('🎉 Your HMMC Donor Account is Approved!');
            });

            Log::info("Approval email credentials sent to: {$email}");
            return true;

        } catch (\Exception $e) {
            Log::error('Approval email send failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Format phone number for WhatsApp
     */
    private function formatPhoneNumber($phone)
    {
        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $phone);
        
        // If starts with 0, replace with country code
        if (substr($phone, 0, 1) === '0') {
            $phone = '60' . substr($phone, 1); // Malaysia country code
        }
        
        // Ensure it doesn't start with + for WhatsApp link
        return ltrim($phone, '+');
    }

    /**
     * Auto-open WhatsApp link in browser
     */
    private function openWhatsAppLink($link)
    {
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                shell_exec("start \"\" \"{$link}\"");
            } elseif (PHP_OS_FAMILY === 'Darwin') { // Mac
                shell_exec("open \"{$link}\"");
            } else { // Linux
                shell_exec("xdg-open \"{$link}\"");
            }
            
            Log::info("Auto-opened WhatsApp link for testing");
        } catch (\Exception $e) {
            Log::warning('Could not auto-open WhatsApp: ' . $e->getMessage());
        }
    }

    /**
     * Generate temporary password for manual sending
     */
    private function generateTemporaryPassword(): string
    {
        $length = 10;
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        
        return $password;
    }

    /**
     * Get result message based on sending status
     */
    private function getSendResultMessage($whatsappSent, $emailSent)
    {
        if ($whatsappSent && $emailSent) {
            return 'Credentials sent via WhatsApp and Email successfully!';
        } elseif ($whatsappSent) {
            return 'Credentials sent via WhatsApp successfully!';
        } elseif ($emailSent) {
            return 'Credentials sent via Email successfully!';
        } else {
            return 'No contact methods available for sending credentials.';
        }
    }

    /**
     * Update screening status and send credentials if passed
     */
    public function updateScreening(Request $request, $donorToBeId)
    {
        $request->validate([
            'dtb_ScreeningStatus' => 'required|in:passed,failed',
            'dtb_ScreeningRemark' => 'required_if:dtb_ScreeningStatus,failed|nullable|string|max:500',
            'dtb_ScreeningNotes' => 'nullable|string|max:1000',
            'ns_ID' => 'nullable|exists:nurse,ns_ID',
        ]);

        try {
            DB::beginTransaction();

            // Get donor_to_be record
            $donorToBe = DB::table('donor_to_be')->where('dtb_id', $donorToBeId)->first();
            
            if (!$donorToBe) {
                return back()->with('error', 'Screening record not found.');
            }

            // Update screening record
            DB::table('donor_to_be')
                ->where('dtb_id', $donorToBeId)
                ->update([
                    'dtb_ScreeningStatus' => $request->dtb_ScreeningStatus,
                    'dtb_ScreeningRemark' => $request->dtb_ScreeningRemark,
                    'dtb_ScreeningNotes' => $request->dtb_ScreeningNotes,
                    'dtb_ScreenedAt' => now(),
                    'ns_ID' => $request->ns_ID,
                ]);

            // Get donor record
            $donor = DB::table('donor')->where('dn_ID', $donorToBe->dn_ID)->first();

            if ($request->dtb_ScreeningStatus === 'passed') {
                // Create user account for login
                DB::table('users')->updateOrInsert(
                    ['email' => $donor->dn_Email],
                    [
                        'name' => $donor->dn_FullName,
                        'password' => $donor->dn_Password,
                        'role' => 'donor',
                        'role_id' => $donor->dn_ID,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // Send approval credentials
                $this->sendApprovalCredentials($donor);
            }

            DB::commit();

            return redirect()->route('admin.donor-screening.index')
                ->with('success', 'Screening completed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Screening failed: ' . $e->getMessage());
        }
    }

    /**
     * Send approval credentials (for automatic sending after screening)
     */
    private function sendApprovalCredentials($donor)
    {
        $username = $donor->dn_Username;
        $temporaryPassword = $this->generateTemporaryPassword();

        $whatsappSent = false;
        $emailSent = false;

        // Send WhatsApp if contact provided
        if ($donor->dn_Contact && $donor->dn_Contact !== 'N/A') {
            $whatsappSent = $this->sendWhatsAppCredentials(
                $donor->dn_Contact,
                $username,
                $temporaryPassword,
                $donor->dn_FullName
            );
        }

        // Send Email if email provided
        if ($donor->dn_Email && $donor->dn_Email !== 'N/A') {
            $emailSent = $this->sendEmailCredentials(
                $donor->dn_Email,
                $username,
                $temporaryPassword,
                $donor->dn_FullName
            );
        }

        Log::info("Auto-sent credentials for approved donor: {$donor->dn_FullName}", [
            'whatsapp_sent' => $whatsappSent,
            'email_sent' => $emailSent
        ]);

        return [$whatsappSent, $emailSent];
    }

    public function validateField(Request $request)
    {
        try {
            $request->validate([
                'field' => 'required|string',
                'value' => 'required',
                'role' => 'required|string'
            ]);

            $field = $request->field;
            $value = $request->value;
            $role = $request->role;

            \Log::info('Field validation request:', [
                'field' => $field,
                'value' => $value,
                'role' => $role
            ]);

            // Define the correct column names for each role and field
            $columnMapping = [
                'admin' => [
                    'email' => 'ad_Email',
                    'nric' => 'ad_NRIC', 
                    'username' => 'ad_Username',
                    'contact' => 'ad_Contact'
                ],
                'doctor' => [
                    'email' => 'dr_Email',
                    'nric' => 'dr_NRIC',
                    'username' => 'dr_Username', 
                    'contact' => 'dr_Contact'
                ],
                'nurse' => [
                    'email' => 'ns_Email',
                    'nric' => 'ns_NRIC',
                    'username' => 'ns_Username',
                    'contact' => 'ns_Contact'
                ],
                'labtech' => [
                    'email' => 'lt_Email',
                    'nric' => 'lt_NRIC',
                    'username' => 'lt_Username',
                    'contact' => 'lt_Contact'
                ],
                'shariah' => [
                    'email' => 'sc_Email',
                    'nric' => 'sc_NRIC',
                    'username' => 'sc_Username',
                    'contact' => 'sc_Contact'
                ],
                'parent' => [
                    'email' => 'pr_Email',
                    'nric' => 'pr_NRIC',
                    'username' => 'pr_Username',
                    'contact' => 'pr_Contact'
                ],
                'donor' => [
                    'email' => 'dn_Email',
                    'nric' => 'dn_NRIC',
                    'username' => 'dn_Username',
                    'contact' => 'dn_Contact'
                ]
            ];

            $messageMapping = [
                'email' => 'Email already exists',
                'nric' => 'NRIC already registered',
                'username' => 'Username already taken',
                'contact' => 'Contact number already registered'
            ];

            // Check if role and field are valid
            if (!isset($columnMapping[$role]) || !isset($columnMapping[$role][$field])) {
                \Log::error('Invalid field mapping:', [
                    'role' => $role,
                    'field' => $field,
                    'available_roles' => array_keys($columnMapping),
                    'available_fields' => isset($columnMapping[$role]) ? array_keys($columnMapping[$role]) : 'none'
                ]);
                
                return response()->json([
                    'valid' => false,
                    'message' => 'This field cannot be validated at the moment. Please try again.'
                ]);
            }

            $tableConfig = $this->roleConfig[$role] ?? null;
            if (!$tableConfig) {
                \Log::error('Invalid role configuration:', ['role' => $role]);
                return response()->json([
                    'valid' => false,
                    'message' => 'Invalid user role configuration.'
                ]);
            }

            $columnName = $columnMapping[$role][$field];
            $errorMessage = $messageMapping[$field];

            \Log::info('Database query details:', [
                'table' => $tableConfig['table'],
                'column' => $columnName,
                'value' => $value
            ]);

            // Check if value exists in the role-specific table
            $exists = false;
            foreach ($this->roleConfig as $checkRole => $config) {
                if (isset($columnMapping[$checkRole][$field])) {
                    $checkColumn = $columnMapping[$checkRole][$field];
                    $exists = DB::table($config['table'])
                        ->where($checkColumn, $value)
                        ->exists();
                    
                    if ($exists) break;
                }
            }

            \Log::info('Query result:', [
                'exists' => $exists,
                'sql' => DB::table($tableConfig['table'])->where($columnName, $value)->toSql()
            ]);

            return response()->json([
                'valid' => !$exists,
                'message' => $exists ? $errorMessage : 'Available'
            ]);

        } catch (\Exception $e) {
            \Log::error('Field validation error: ' . $e->getMessage(), [
                'field' => $request->field,
                'role' => $request->role,
                'value' => $request->value,
                'trace' => $e->getTraceAsString()
            ]);

            // Return a user-friendly error message
            return response()->json([
                'valid' => false,
                'message' => 'Validation service is temporarily unavailable. Please try again in a moment.'
            ]);
        }
    }
}