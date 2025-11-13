<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ParentModel;
use App\Models\LabTech;
use App\Models\Doctor;
use App\Models\ShariahCommittee;
use App\Models\Nurse;
use App\Models\HmmcAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $roles = [
        'parent', 'shariah', 'nurse', 'doctor', 'labtech', 'admin'
    ];

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
            'nric'     => 'required_if:role,admin,doctor,labtech,shariah,nurse,parent|string|max:20',
        ]);

        \Log::info('Validation passed');

        $password = Hash::make($request->input('password'));

        switch ($role) {
            case 'admin':
                $user = new HmmcAdmin([
                    'ad_Name'     => $request->name,
                    'ad_NRIC'     => $request->nric,
                    'ad_Username' => $request->username,
                    'ad_Password' => $password,
                    'ad_Email'    => $request->email,
                    'ad_Contact'  => $request->contact ?? null,
                    'ad_Address'  => $request->address ?? null,
                    'ad_Gender'   => $request->gender ?? null,
                ]);
                break;

            case 'doctor':
                $user = new Doctor([
                    'dr_Name'          => $request->name,
                    'dr_NRIC'          => $request->nric,
                    'dr_Username'      => $request->username,
                    'dr_Password'      => $password,
                    'dr_Email'         => $request->email,
                    'dr_Contact'       => $request->contact ?? null,
                    'dr_Address'       => $request->address ?? null,
                    'dr_Qualification' => $request->qualification ?? null,
                    'dr_Cerification'  => $request->certification ?? null, // match DB spelling
                    'dr_Institution'   => $request->institution ?? null,
                    'dr_Specialization'=> $request->specialization ?? null,
                    'dr_YearsOfExperience' => $request->experience ?? 0,
                ]);
                break;

            case 'nurse':
                $user = new Nurse([
                    'ns_Name'          => $request->name,
                    'ns_NRIC'          => $request->nric,
                    'ns_Username'      => $request->username,
                    'ns_Password'      => $password,
                    'ns_Email'         => $request->email,
                    'ns_Contact'       => $request->contact ?? null,
                    'ns_Address'       => $request->address ?? null,
                    'ns_Qualification' => $request->qualification ?? null,
                    'ns_Cerification'  => $request->certification ?? null,
                    'ns_Institution'   => $request->institution ?? null,
                    'ns_Specialization'=> $request->specialization ?? null,
                    'ns_YearsOfExperience' => $request->experience ?? 0,
                ]);
                break;

            case 'labtech':
                $user = new LabTech([
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
                ]);
                break;

            case 'shariah':
                $user = new ShariahCommittee([
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
                ]);
                break;

            case 'parent':
                $user = new ParentModel([
                    'pr_Name'              => $request->name,
                    'pr_NRIC'              => $request->nric,
                    'pr_Address'           => $request->address ?? null,
                    'pr_Contact'           => $request->contact ?? null,
                    'pr_Email'             => $request->email,
                    'pr_BabyName'          => $request->baby_name ?? null,
                    'pr_BabyDOB'           => $request->baby_dob ?? null,
                    'pr_BabyGender'        => $request->baby_gender ?? null,
                    'pr_BabyBirthWeight'   => $request->baby_birth_weight ?? null,
                    'pr_BabyCurrentWeight' => $request->baby_current_weight ?? null,
                    'pr_Password'          => $password,
                ]);
                break;
        }

        \Log::info('User object created', $user->toArray());
        
        $user->save();
        
        \Log::info('User saved successfully');

        return redirect()->route('hmmc.create-new-user', ['role' => $role])
                 ->with('success', ucfirst($role) . ' created successfully!');

    } catch (\Exception $e) {
        \Log::error('Error saving user: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return back()->withInput()->with('error', 'Error creating user: ' . $e->getMessage());
    }
    }
}
