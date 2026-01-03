<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonorToBe;
use App\Models\Donor;
use App\Models\User;
use App\Models\Nurse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DonorScreeningController extends Controller
{
    /**
     * Display all pending and completed screenings
     */
    public function index()
    {
        $pendingScreenings = DonorToBe::with(['donor', 'nurse'])
            ->pending()
            ->latest()
            ->get();

        $completedScreenings = DonorToBe::with(['donor', 'nurse'])
            ->screened()
            ->latest('dtb_ScreenedAt')
            ->get();

        $nurses = Nurse::all(); // For assigning nurses to screenings

        return view('admin.donor-screening.index', compact('pendingScreenings', 'completedScreenings', 'nurses'));
    }

    /**
     * Show individual screening details
     */
    public function show(DonorToBe $donorToBe)
    {
        $donorToBe->load(['donor', 'nurse']);
        $nurses = Nurse::all();
        
        return view('admin.donor-screening.show', compact('donorToBe', 'nurses'));
    }

    /**
     * Show edit form for screening
     */
    public function edit(DonorToBe $donorToBe)
    {
        $donorToBe->load(['donor', 'nurse']);
        $nurses = Nurse::all();
        
        return view('admin.donor-screening.edit', compact('donorToBe', 'nurses'));
    }

    /**
     * Update screening status
     */
    public function updateScreening(Request $request, DonorToBe $donorToBe)
    {
        $request->validate([
            'dtb_ScreeningStatus' => 'required|in:passed,failed',
            'dtb_ScreeningRemark' => 'required_if:dtb_ScreeningStatus,failed|nullable|string|max:500',
            'dtb_ScreeningNotes' => 'nullable|string|max:1000',
            'ns_ID' => 'nullable|exists:nurse,ns_ID',
        ]);

        try {
            DB::beginTransaction();

            $donorToBe->update([
                'dtb_ScreeningStatus' => $request->dtb_ScreeningStatus,
                'dtb_ScreeningRemark' => $request->dtb_ScreeningRemark,
                'dtb_ScreeningNotes' => $request->dtb_ScreeningNotes,
                'dtb_ScreenedAt' => now(),
                'ns_ID' => $request->ns_ID,
            ]);

            // Update donor active status
            $donor = $donorToBe->donor;
            $isActive = $request->dtb_ScreeningStatus === 'passed';
            $donor->update(['is_active' => $isActive]);

            if ($isActive) {
                // Create user account for login
                User::updateOrCreate(
                    ['email' => $donor->dn_Email],
                    [
                        'name' => $donor->dn_FullName,
                        'password' => $donor->dn_Password,
                        'role' => 'donor',
                        'role_id' => $donor->dn_ID,
                    ]
                );

                // Send approval notification
                $this->sendApprovalNotification($donor);
            } else {
                // Send rejection notification
                $this->sendRejectionNotification($donor, $request->dtb_ScreeningRemark);
            }

            DB::commit();

            return redirect()->route('admin.donor-screening.index')
                ->with('success', 'Screening completed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Screening update error: ' . $e->getMessage());
            return back()->with('error', 'Screening failed: ' . $e->getMessage());
        }
    }

    /**
     * Bulk screening actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'screening_ids' => 'required|array',
            'screening_ids.*' => 'exists:donor_to_be,dtb_id',
            'bulk_remark' => 'required_if:action,reject|nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $screenings = DonorToBe::whereIn('dtb_id', $request->screening_ids)->get();

            foreach ($screenings as $screening) {
                $status = $request->action === 'approve' ? 'passed' : 'failed';
                
                $screening->update([
                    'dtb_ScreeningStatus' => $status,
                    'dtb_ScreeningRemark' => $request->bulk_remark,
                    'dtb_ScreenedAt' => now(),
                    'ns_ID' => auth()->user()->role_id, // Assuming admin is screening
                ]);

                $donor = $screening->donor;
                $donor->update(['is_active' => $status === 'passed']);

                if ($status === 'passed') {
                    User::updateOrCreate(
                        ['email' => $donor->dn_Email],
                        [
                            'name' => $donor->dn_FullName,
                            'password' => $donor->dn_Password,
                            'role' => 'donor',
                            'role_id' => $donor->dn_ID,
                        ]
                    );
                    $this->sendApprovalNotification($donor);
                } else {
                    $this->sendRejectionNotification($donor, $request->bulk_remark);
                }
            }

            DB::commit();

            return redirect()->route('admin.donor-screening.index')
                ->with('success', count($screenings) . ' screenings updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk screening error: ' . $e->getMessage());
            return back()->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }

    /**
     * API Methods for AJAX operations
     */
    public function apiPendingScreenings(): JsonResponse
    {
        $pendingScreenings = DonorToBe::with(['donor'])
            ->pending()
            ->latest()
            ->get()
            ->map(function ($screening) {
                return [
                    'id' => $screening->dtb_id,
                    'nric' => $screening->donor->dn_NRIC,
                    'name' => $screening->donor->dn_FullName,
                    'contact' => $screening->donor->dn_Contact,
                    'registered_at' => $screening->donor->created_at->format('d M Y'),
                ];
            });

        return response()->json($pendingScreenings);
    }

    public function apiUpdateScreening(Request $request, DonorToBe $donorToBe): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:passed,failed',
            'remark' => 'required_if:status,failed|nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $donorToBe->update([
                'dtb_ScreeningStatus' => $request->status,
                'dtb_ScreeningRemark' => $request->remark,
                'dtb_ScreenedAt' => now(),
                'ns_ID' => auth()->id(),
            ]);

            $donor = $donorToBe->donor;
            $donor->update(['is_active' => $request->status === 'passed']);

            if ($request->status === 'passed') {
                User::updateOrCreate(
                    ['email' => $donor->dn_Email],
                    [
                        'name' => $donor->dn_FullName,
                        'password' => $donor->dn_Password,
                        'role' => 'donor',
                        'role_id' => $donor->dn_ID,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Screening updated successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('API screening update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Screening update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiScreeningStats(): JsonResponse
    {
        $stats = [
            'pending' => DonorToBe::pending()->count(),
            'passed' => DonorToBe::where('dtb_ScreeningStatus', 'passed')->count(),
            'failed' => DonorToBe::where('dtb_ScreeningStatus', 'failed')->count(),
            'total' => DonorToBe::count(),
        ];

        return response()->json($stats);
    }

    public function showAllDonorToBe()
    {
        // Load all candidates from DB
        $candidates = DonorToBe::orderBy('created_at', 'desc')->get();

        return view('nurse.donor-candidates', [
            'candidates' => $candidates
        ]);
    }



    /**
     * Approve donor candidate screening
     */
    public function approveDonorToBe(Request $request, $id)
    {
        $candidate = DonorToBe::findOrFail($id);

        // Update statuses
        $candidate->screening_status = 'Approved';
        $candidate->eligible_status  = 'Approved';

        // Optional remarks
        if ($request->filled('remarks')) {
            $candidate->remarks = $request->remarks;
        }

        $candidate->save();

        Log::info("Candidate approved by nurse.", [
            'candidate_id' => $candidate->id,
            'nurse_id' => auth()->id()
        ]);

        return redirect()
            ->back()
            ->with('success', 'Candidate screening approved successfully.');
    }



    /**
     * Reject donor candidate screening
     */
    public function rejectDonorToBe(Request $request, $id)
    {
        // Validate reason
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $candidate = DonorToBe::findOrFail($id);

        // Update statuses
        $candidate->screening_status = 'Rejected';
        $candidate->eligible_status  = 'Rejected';

        // Save rejection reason
        $candidate->rejection_reason = $request->reason;

        $candidate->save();

        Log::warning("Candidate screening rejected.", [
            'candidate_id' => $candidate->id,
            'reason' => $request->reason,
            'nurse_id' => auth()->id()
        ]);

        return redirect()
            ->back()
            ->with('success', 'Candidate screening rejected.');
    }

    /**
     * Notification methods
     */
    private function sendApprovalNotification(Donor $donor)
    {
        try {
            $message = "🎉 Congratulations {$donor->dn_FullName}!\n\n" .
                      "Your HMMC donor application has been APPROVED!\n\n" .
                      "You can now login to your account:\n" .
                      "🔗 " . route('login') . "\n\n" .
                      "Username: {$donor->dn_Username}\n" .
                      "You'll set a new password on first login.\n\n" .
                      "Welcome to HMMC! ❤️";

            // Send WhatsApp notification
            if ($donor->dn_Contact) {
                $this->sendWhatsAppMessage($donor->dn_Contact, $message);
            }

            // Send email notification
            if ($donor->dn_Email) {
                Mail::send('emails.donor-approved', [
                    'donor' => $donor,
                    'loginUrl' => route('login')
                ], function ($message) use ($donor) {
                    $message->to($donor->dn_Email)
                            ->subject('🎉 Your HMMC Donor Application Has Been Approved!');
                });
            }

            Log::info("Approval notification sent to: {$donor->dn_FullName}");

        } catch (\Exception $e) {
            Log::error('Approval notification failed: ' . $e->getMessage());
        }
    }

    private function sendRejectionNotification(Donor $donor, $remark)
    {
        try {
            $message = "Dear {$donor->dn_FullName},\n\n" .
                      "Thank you for your interest in becoming a donor with HMMC.\n\n" .
                      "After careful review, we regret to inform you that your application " .
                      "could not be approved at this time.\n\n" .
                      "Reason: {$remark}\n\n" .
                      "You may reapply after addressing the above concerns.\n\n" .
                      "Thank you for your understanding.";

            // Send WhatsApp notification
            if ($donor->dn_Contact) {
                $this->sendWhatsAppMessage($donor->dn_Contact, $message);
            }

            // Send email notification
            if ($donor->dn_Email) {
                Mail::send('emails.donor-rejected', [
                    'donor' => $donor,
                    'remark' => $remark
                ], function ($message) use ($donor) {
                    $message->to($donor->dn_Email)
                            ->subject('HMMC Donor Application Update');
                });
            }

            Log::info("Rejection notification sent to: {$donor->dn_FullName}");

        } catch (\Exception $e) {
            Log::error('Rejection notification failed: ' . $e->getMessage());
        }
    }

    private function sendWhatsAppMessage($phone, $message)
    {
        try {
            $cleanPhone = $this->formatPhoneNumber($phone);
            $encodedMessage = urlencode($message);
            $whatsappLink = "https://wa.me/{$cleanPhone}?text={$encodedMessage}";

            // In production, you would use Twilio API or similar service
            Log::info("WhatsApp message prepared: {$whatsappLink}");

            return true;

        } catch (\Exception $e) {
            Log::error('WhatsApp message failed: ' . $e->getMessage());
            return false;
        }
    }

    private function formatPhoneNumber($phone): string
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
}