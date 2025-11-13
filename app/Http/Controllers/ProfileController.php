<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

// Import your role-based models
use App\Models\Donor;
use App\Models\ParentProfile;
use App\Models\Nurse;
use App\Models\Clinician;
use App\Models\HmmcAdmin;
use App\Models\ShariahCommittee;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $profile = $this->getProfileData($user);

        return view('profile.edit', compact('user', 'profile'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Update role-specific data
        $this->updateRoleProfile($user, $request);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Get profile data based on user role.
     */
    private function getProfileData($user)
    {
        switch ($user->role) {
            case 'donor':
                return Donor::where('user_id', $user->id)->first();
            case 'parent':
                return ParentProfile::where('user_id', $user->id)->first();
            case 'nurse':
                return Nurse::where('user_id', $user->id)->first();
            case 'clinician':
                return Clinician::where('user_id', $user->id)->first();
            case 'admin':
                return HmmcAdmin::where('user_id', $user->id)->first();
            case 'shariah':
                return ShariahCommittee::where('user_id', $user->id)->first();
            default:
                return null;
        }
    }

    /**
     * Update role-specific profile information.
     */
    private function updateRoleProfile($user, $request)
    {
        switch ($user->role) {
            case 'donor':
                Donor::updateOrCreate(
                    ['user_id' => $user->id],
                    $request->only(['dn_Address', 'dn_Contact', 'dn_DOB'])
                );
                break;
            case 'parent':
                ParentProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    $request->only(['pr_Address', 'pr_BabyName', 'pr_BabyDOB'])
                );
                break;
            case 'nurse':
                Nurse::updateOrCreate(
                    ['user_id' => $user->id],
                    $request->only(['ns_Address', 'ns_Institution', 'ns_Qualification'])
                );
                break;
            case 'clinician':
                Clinician::updateOrCreate(
                    ['user_id' => $user->id],
                    $request->only(['cn_Address', 'cn_Specialization', 'cn_Institution'])
                );
                break;
            case 'admin':
                HmmcAdmin::updateOrCreate(
                    ['user_id' => $user->id],
                    $request->only(['ad_Address', 'ad_Contact'])
                );
                break;
            case 'shariah':
                ShariahCommittee::updateOrCreate(
                    ['user_id' => $user->id],
                    $request->only(['sc_Address', 'sc_Qualification'])
                );
                break;
        }
    }
}
