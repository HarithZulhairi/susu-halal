<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donor;
use App\Models\Milk;
use Carbon\Carbon;
use App\Models\PreBottle;
use App\Models\PostBottle;

class MilkController extends Controller
{
    public function viewMilkDonor(Request $request)
    {
        // 1. GET CURRENT DONOR
        // Assuming your 'donor' table has a 'user_id' column linking to the Auth user.
        // If you use 'role_id' or another method, adjust this line accordingly.
        $currentDonor = Donor::where('user_id', auth()->id())->first();

        // Safety check: if no donor profile exists for this user
        if (!$currentDonor) {
            abort(403, 'No donor profile found for this account.');
        }

        // 2. START QUERY (Filter by current Donor ID immediately)
        $query = Milk::with(['preBottles', 'postBottles'])
                     ->where('dn_ID', $currentDonor->dn_ID);

        // --- SEARCH INPUT (Only search Milk ID, removing Donor Name search) ---
        if ($request->filled('searchInput')) {
            $search = $request->input('searchInput');
            $query->where('milk_ID', 'like', "%{$search}%");
        }

        // --- STATUS FILTER ---
        if ($request->filled('filterStatus')) {
            $status = $request->input('filterStatus');
            if (strtolower($status) === 'not yet started') {
                $query->where(function($q) {
                    $q->where('milk_Status', 'Not Yet Started')
                      ->orWhereNull('milk_Status');
                });
            } else {
                $query->where('milk_Status', $status);
            }
        }

        // --- VOLUME FILTER ---
        if ($request->filled('volumeMin')) {
            $query->where('milk_volume', '>=', (float) $request->input('volumeMin'));
        }
        if ($request->filled('volumeMax')) {
            $query->where('milk_volume', '<=', (float) $request->input('volumeMax'));
        }

        // --- SHARIAH FILTER ---
        if ($request->filled('filterShariah')) {
            $sh = $request->input('filterShariah');
            if (strtolower($sh) === 'not yet reviewed') {
                $query->whereNull('milk_shariahApproval');
            } elseif (strtolower($sh) === 'approved') {
                $query->where('milk_shariahApproval', true);
            } elseif (strtolower($sh) === 'rejected') {
                $query->where('milk_shariahApproval', false);
            }
        }

        // Get Results
        $milks = $query->orderByDesc('created_at')->get();

        // We don't need to pass all $donors, just the current user's records
        return view('donor.donor_manage-milk-records', compact('milks'));
    }


    public function viewMilkLabtech(Request $request)
    {
        $donors = Donor::all();

        // Start Query with Relationships
        $query = Milk::with(['donor', 'preBottles', 'postBottles']);

        // --- 1. SEARCH INPUT (ID or Donor Name) ---
        if ($request->filled('searchInput')) {
            $search = $request->input('searchInput');
            $query->where(function($q) use ($search) {
                $q->where('milk_ID', 'like', "%{$search}%")
                ->orWhereHas('donor', function($dq) use ($search) {
                    $dq->where('dn_FullName', 'like', "%{$search}%");
                });
            });
        }

        // --- 2. STATUS FILTER ---
        if ($request->filled('filterStatus')) {
            $status = $request->input('filterStatus');
            if (strtolower($status) === 'not yet started') {
                // Check for explicit "Not Yet Started" OR null
                $query->where(function($q) {
                    $q->where('milk_Status', 'Not Yet Started')
                    ->orWhereNull('milk_Status');
                });
            } else {
                $query->where('milk_Status', $status);
            }
        }

        // --- 3. VOLUME RANGE FILTER ---
        if ($request->filled('volumeMin')) {
            $query->where('milk_volume', '>=', (float) $request->input('volumeMin'));
        }
        if ($request->filled('volumeMax')) {
            $query->where('milk_volume', '<=', (float) $request->input('volumeMax'));
        }

        // --- 4. EXPIRY DATE FILTER (REMOVED) ---
        // Since milk_expiryDate column is deleted, we cannot filter by it directly on the Milk table.
        // If you want to filter by the *final product expiry*, you would need to query the related postBottles.
        // For now, I have removed it to prevent SQL errors. 
        /* if ($request->filled('expiryFrom')) { ... }
        if ($request->filled('expiryTo')) { ... }
        */

        // --- 5. SHARIAH APPROVAL FILTER ---
        if ($request->filled('filterShariah')) {
            $sh = $request->input('filterShariah');
            if (strtolower($sh) === 'not yet reviewed') {
                $query->whereNull('milk_shariahApproval');
            } elseif (strtolower($sh) === 'approved') {
                $query->where('milk_shariahApproval', true);
            } elseif (strtolower($sh) === 'rejected') {
                $query->where('milk_shariahApproval', false);
            }
        }

        // Get Results
        $milks = $query->orderByDesc('created_at')->get();

        return view('labtech.labtech_manage-milk-records', compact('donors', 'milks'));
    }

    public function viewMilkNurse(Request $request)
    {
        $donors = Donor::all();

        // Start Query with Relationships
        $query = Milk::with(['donor', 'preBottles', 'postBottles']);

        // --- 1. SEARCH INPUT (ID or Donor Name) ---
        if ($request->filled('searchInput')) {
            $search = $request->input('searchInput');
            $query->where(function($q) use ($search) {
                $q->where('milk_ID', 'like', "%{$search}%")
                ->orWhereHas('donor', function($dq) use ($search) {
                    $dq->where('dn_FullName', 'like', "%{$search}%");
                });
            });
        }

        // --- 2. STATUS FILTER ---
        if ($request->filled('filterStatus')) {
            $status = $request->input('filterStatus');
            if (strtolower($status) === 'not yet started') {
                // Check for explicit "Not Yet Started" OR null
                $query->where(function($q) {
                    $q->where('milk_Status', 'Not Yet Started')
                    ->orWhereNull('milk_Status');
                });
            } else {
                $query->where('milk_Status', $status);
            }
        }

        // --- 3. VOLUME RANGE FILTER ---
        if ($request->filled('volumeMin')) {
            $query->where('milk_volume', '>=', (float) $request->input('volumeMin'));
        }
        if ($request->filled('volumeMax')) {
            $query->where('milk_volume', '<=', (float) $request->input('volumeMax'));
        }

        // --- 4. EXPIRY DATE FILTER (REMOVED) ---
        // Since milk_expiryDate column is deleted, we cannot filter by it directly on the Milk table.
        // If you want to filter by the *final product expiry*, you would need to query the related postBottles.
        // For now, I have removed it to prevent SQL errors. 
        /* if ($request->filled('expiryFrom')) { ... }
        if ($request->filled('expiryTo')) { ... }
        */

        // --- 5. SHARIAH APPROVAL FILTER ---
        if ($request->filled('filterShariah')) {
            $sh = $request->input('filterShariah');
            if (strtolower($sh) === 'not yet reviewed') {
                $query->whereNull('milk_shariahApproval');
            } elseif (strtolower($sh) === 'approved') {
                $query->where('milk_shariahApproval', true);
            } elseif (strtolower($sh) === 'rejected') {
                $query->where('milk_shariahApproval', false);
            }
        }

        // Get Results
        $milks = $query->orderByDesc('created_at')->get();

        return view('nurse.nurse_manage-milk-records', compact('donors', 'milks'));
    }

    public function viewMilkShariah(Request $request)
    {
        $donors = Donor::all();

        // Start Query with Relationships
        $query = Milk::with(['donor', 'preBottles', 'postBottles']);

        // --- 1. SEARCH INPUT (ID or Donor Name) ---
        if ($request->filled('searchInput')) {
            $search = $request->input('searchInput');
            $query->where(function($q) use ($search) {
                $q->where('milk_ID', 'like', "%{$search}%")
                ->orWhereHas('donor', function($dq) use ($search) {
                    $dq->where('dn_FullName', 'like', "%{$search}%");
                });
            });
        }

        // --- 2. STATUS FILTER ---
        if ($request->filled('filterStatus')) {
            $status = $request->input('filterStatus');
            if (strtolower($status) === 'not yet started') {
                // Check for explicit "Not Yet Started" OR null
                $query->where(function($q) {
                    $q->where('milk_Status', 'Not Yet Started')
                    ->orWhereNull('milk_Status');
                });
            } else {
                $query->where('milk_Status', $status);
            }
        }

        // --- 3. VOLUME RANGE FILTER ---
        if ($request->filled('volumeMin')) {
            $query->where('milk_volume', '>=', (float) $request->input('volumeMin'));
        }
        if ($request->filled('volumeMax')) {
            $query->where('milk_volume', '<=', (float) $request->input('volumeMax'));
        }

        // --- 4. EXPIRY DATE FILTER (REMOVED) ---
        // Since milk_expiryDate column is deleted, we cannot filter by it directly on the Milk table.
        // If you want to filter by the *final product expiry*, you would need to query the related postBottles.
        // For now, I have removed it to prevent SQL errors. 
        /* if ($request->filled('expiryFrom')) { ... }
        if ($request->filled('expiryTo')) { ... }
        */

        // --- 5. SHARIAH APPROVAL FILTER ---
        if ($request->filled('filterShariah')) {
            $sh = $request->input('filterShariah');
            if (strtolower($sh) === 'not yet reviewed') {
                $query->whereNull('milk_shariahApproval');
            } elseif (strtolower($sh) === 'approved') {
                $query->where('milk_shariahApproval', true);
            } elseif (strtolower($sh) === 'rejected') {
                $query->where('milk_shariahApproval', false);
            }
        }

        // Get Results
        $milks = $query->orderByDesc('created_at')->get();

        return view('shariah.shariah_manage-milk-records', compact('donors', 'milks'));
    }

    public function viewMilkHMMC(Request $request)
    {
        $donors = Donor::all();

        // Build query and apply filters from request (GET)
        $query = Milk::with('donor');
        

        // Search by donor name or milk ID
        if ($request->filled('searchInput')) {
            $search = $request->input('searchInput');
            $query->where(function($q) use ($search) {
                $q->where('milk_ID', 'like', "%{$search}%")
                  ->orWhereHas('donor', function($dq) use ($search) {
                      $dq->where('dn_FullName', 'like', "%{$search}%");
                  });
            });
        }

        // Clinical status filter (exact match or treat 'Not Yet Started' as null)
        if ($request->filled('filterStatus')) {
            $status = $request->input('filterStatus');
            if (strtolower($status) === 'not yet started') {
                $query->whereNull('milk_Status');
            } else {
                $query->where('milk_Status', $status);
            }
        }

        // Volume range filter
        if ($request->filled('volumeMin')) {
            $query->where('milk_volume', '>=', (float) $request->input('volumeMin'));
        }
        if ($request->filled('volumeMax')) {
            $query->where('milk_volume', '<=', (float) $request->input('volumeMax'));
        }

        // Expiry date range
        if ($request->filled('expiryFrom')) {
            $query->whereDate('milk_expiryDate', '>=', $request->input('expiryFrom'));
        }
        if ($request->filled('expiryTo')) {
            $query->whereDate('milk_expiryDate', '<=', $request->input('expiryTo'));
        }

        // Shariah approval
        if ($request->filled('filterShariah')) {
            $sh = $request->input('filterShariah');
            if (strtolower($sh) === 'not yet reviewed') {
                $query->whereNull('milk_shariahApproval');
            } elseif (strtolower($sh) === 'approved') {
                $query->where('milk_shariahApproval', true);
            } elseif (strtolower($sh) === 'rejected') {
                $query->where('milk_shariahApproval', false);
            }
        }

        $milks = $query->orderByDesc('created_at')->get();

        return view('hmmc.hmmc_manage-milk-records', compact('donors', 'milks'));
    }

    public function viewMilkProcessingShariah($id)
    {
        // Eager load all necessary relationships for the audit timeline
        $milk = Milk::with(['donor', 'preBottles', 'postBottles'])->findOrFail($id);

        return view('shariah.shariah_view-milk-processing', compact('milk'));
    }

    public function updateDecision(Request $request, $id)
    {
        // 1. Validate Input
        $request->validate([
            'approval' => 'required|boolean', // 1 or 0
            'remarks'  => 'nullable|string|max:1000'
        ]);

        // 2. Find Record Manually (Safest for custom Primary Keys)
        $milk = Milk::findOrFail($id);

        // 3. Update
        $updated = $milk->update([
            'milk_shariahApproval'     => $request->approval,
            'milk_shariahRemarks'      => $request->remarks,
            'milk_shariahApprovalDate' => now(),
        ]);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Shariah decision recorded successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update database.'
            ], 500);
        }
    }

    // 2. Update Method
    public function updateMilkRecordHMMC(Request $request, $id)
    {
        $request->validate([
            'milk_volume' => 'required|numeric|min:0',
            'milk_shariahApprovalDate' => 'nullable|date',
            'milk_shariahRemarks' => 'nullable|string'
        ]);

        $milk = Milk::findOrFail($id);
        
        $milk->update([
            'milk_volume' => $request->milk_volume,
            'milk_expiryDate' => $request->milk_expiryDate,
            'milk_shariahApprovalDate' => $request->milk_shariahApprovalDate,
            'milk_shariahRemarks' => $request->milk_shariahRemarks,
        ]);

        return response()->json(['success' => true, 'message' => 'Record updated successfully']);
    }

    public function deleteMilkRecordHMMC($id)
    {
        $milk = Milk::findOrFail($id);
        $milk->delete();

        return response()->json(['success' => true, 'message' => 'Record deleted successfully']);
    }


    public function storeMilkRecord(Request $request)
    {
        // 1. Validation
        $request->validate([
            'dn_ID'       => 'required|exists:donor,dn_ID',
            'milk_volume' => 'required|numeric|min:0.1',
        ]);

        // 2. Create Record (Initializing all 5 Stages)
        Milk::create([
            'dn_ID'           => $request->dn_ID,
            'milk_volume'     => $request->milk_volume,
            'milk_Status'     => 'Not Yet Started', 

            // Shariah Info
            'milk_shariahApproval'     => null,
            'milk_shariahApprovalDate' => null,
            'milk_shariahRemarks'      => null,

            // Stage 1: Screening
            'milk_stage1StartDate' => null,
            'milk_stage1EndDate'   => null,
            'milk_stage1StartTime' => null,
            'milk_stage1EndTime'   => null,
            
            // Stage 2: Thawing
            'milk_stage2StartDate' => null,
            'milk_stage2EndDate'   => null,

            // Stage 3: Pasteurization
            'milk_stage3StartDate' => null,
            'milk_stage3EndDate'   => null,

            // Stage 4: Microbiology (Added)
            'milk_stage4StartDate' => null,
            'milk_stage4EndDate'   => null,

            // Stage 5: Storage (Added)
            'milk_stage5StartDate' => null,
            'milk_stage5EndDate'   => null,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Milk record created successfully! Screening has begun.'
            ]);
        }

        return redirect()
            ->route('labtech.labtech_manage-milk-records')
            ->with('success', 'Milk record created successfully! Screening has begun.');
    }

    public function processMilk(Milk $milk)
    {
        // Load donor, preBottles (Stage 1-2), and postBottles (Stage 3-5)
        $milk->load(['donor', 'preBottles', 'postBottles']);

        return view('labtech.labtech_process-milk', compact('milk'));
    }

    public function updateProcess(Request $request, Milk $milk)
    {
        $stage = $request->stage;

        // Validate only fields relevant to the submitted stage (match form input names)
        if ($stage == 1) {
            $request->validate([
                'milk_stage1StartDate' => 'required|date',
                'milk_stage1StartTime' => 'required',
                'milk_stage1EndDate'   => 'required|date|after_or_equal:milk_stage1StartDate',
                'milk_stage1EndTime'   => 'required',
            ]);
        } elseif ($stage == 2) {
            $request->validate([
                'milk_stage2StartDate' => 'required|date',
                'milk_stage2StartTime' => 'required',
                'milk_stage2EndDate'   => 'required|date|after_or_equal:milk_stage2StartDate',
                'milk_stage2EndTime'   => 'required',
            ]);
        } elseif ($stage == 3) {
            $request->validate([
                'milk_stage3StartDate' => 'required|date',
                'milk_stage3StartTime' => 'required',
                'milk_stage3EndDate'   => 'required|date|after_or_equal:milk_stage3StartDate',
                'milk_stage3EndTime'   => 'required',
            ]);
        }

        $data = [];
        if ($stage == 1) {
            $data = [
                'milk_stage1StartDate' => $request->milk_stage1StartDate ?: now()->format('Y-m-d'),
                'milk_stage1StartTime' => $request->milk_stage1StartTime ?: now()->format('H:i'),
                'milk_stage1EndDate'   => $request->milk_stage1EndDate   ?: now()->format('Y-m-d'),
                'milk_stage1EndTime'   => $request->milk_stage1EndTime   ?: now()->format('H:i'),
                'milk_Status' => 'Screening'
            ];
        } elseif ($stage == 2) {
            $data = [
                'milk_stage2StartDate' => $request->milk_stage2StartDate ?: now()->format('Y-m-d'),
                'milk_stage2StartTime' => $request->milk_stage2StartTime ?: now()->format('H:i'),
                'milk_stage2EndDate'   => $request->milk_stage2EndDate   ?: now()->format('Y-m-d'),
                'milk_stage2EndTime'   => $request->milk_stage2EndTime   ?: now()->format('H:i'),
                'milk_Status' => 'Labelling'
            ];
        } elseif ($stage == 3) {
            $data = [
                'milk_stage3StartDate' => $request->milk_stage3StartDate ?: now()->format('Y-m-d'),
                'milk_stage3StartTime' => $request->milk_stage3StartTime ?: now()->format('H:i'),
                'milk_stage3EndDate'   => $request->milk_stage3EndDate   ?: now()->format('Y-m-d'),
                'milk_stage3EndTime'   => $request->milk_stage3EndTime   ?: now()->format('H:i'),
                'milk_Status' => 'Distributing'
            ];
        }

        $milk->update($data);

        return redirect()->back()->with('success', 'Stage completed successfully!');
    }

    public function saveScreeningResults(Request $request, Milk $milk)
    {
        $request->validate([
            'results' => 'required|array|min:1',
            'results.*.contents' => 'required|string',
            'results.*.tolerance' => 'required|string|in:Passed,Failed,Pending'
        ]);

        $milk->update([
            'milk_stage1Result' => json_encode($request->results),
            'milk_Status' => 'Screening Completed'
        ]);

        return response()->json(['success' => true]);
    }


    // JSON endpoint: return minimal status list for polling in manage view
    public function milkStatuses()
    {
        // Return minimal fields plus stage2 and stage3 datetimes so the manage page
        // can compute remaining durations without visiting each record page.
        $rows = Milk::select(
            'milk_ID',
            'milk_Status',
            // stage 1 (screening)
            'milk_stage1StartDate', 'milk_stage1StartTime', 'milk_stage1EndDate', 'milk_stage1EndTime', 'milk_stage1Result',
            // stage 2 (labelling)
            'milk_stage2StartDate', 'milk_stage2StartTime', 'milk_stage2EndDate', 'milk_stage2EndTime',
            // stage 3 (distributing)
            'milk_stage3StartDate', 'milk_stage3StartTime', 'milk_stage3EndDate', 'milk_stage3EndTime'
        )->get();

        return response()->json($rows);
    }

    /**
     * Delete a milk record.
     * Accepts DELETE requests. Returns JSON for AJAX calls.
     */
    public function destroy(Request $request, Milk $milk)
    {
        try {
            $milk->delete();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('labtech.labtech_manage-milk-records')
                ->with('success', 'Milk record deleted successfully.');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Delete failed'], 500);
            }

            return redirect()->back()->with('error', 'Failed to delete milk record.');
        }
    }


    ////////////////////////////////////////
    // MILK PROCESSING STAGE SAVE METHODS //
    ////////////////////////////////////////

    public function saveStage1(Request $request, $id)
    {
        $request->validate([
            'milk_stage1StartDate' => 'required|date',
            'milk_stage1StartTime' => 'required',
            'bottles' => 'required|array|min:1',
            'bottles.*.volume' => 'required|numeric|min:0',
        ]);

        $milk = Milk::findOrFail($id);

        // --- VALIDATION: Check Total Volume ---
        $totalInputVolume = collect($request->bottles)->sum('volume');
        
        // Allow a tiny margin of error for floating point math, or strict comparison
        if ($totalInputVolume > $milk->milk_volume) {
            return response()->json([
                'success' => false, 
                'message' => "Total bottle volume ($totalInputVolume ml) exceeds the raw milk volume ($milk->milk_volume ml)."
            ], 422);
        }
        // --------------------------------------

        $milk->update([
            'milk_stage1StartDate' => $request->milk_stage1StartDate,
            'milk_stage1StartTime' => $request->milk_stage1StartTime,
            'milk_stage1EndDate' => $request->milk_stage1StartDate, 
            'milk_stage1EndTime' => $request->milk_stage1StartTime,
            'milk_Status' => 'Labelling Completed'
        ]);

        $milk->preBottles()->delete();

        foreach ($request->bottles as $b) {
            PreBottle::create([
                'milk_ID' => $milk->milk_ID,
                'pre_bottle_code' => $b['bottle_id'],
                'pre_volume' => $b['volume'],
                'pre_is_thawed' => false
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function saveStage2(Request $request, $id)
    {
        $request->validate(['bottles' => 'required|array']);
        
        // Update thawing status for each bottle
        foreach ($request->bottles as $b) {
            PreBottle::where('milk_ID', $id)
                ->where('pre_bottle_code', $b['bottle_id'])
                ->update(['pre_is_thawed' => $b['is_thawed']]);
        }

        $milk = Milk::findOrFail($id);
        $milk->update([
            'milk_stage2StartDate' => now(), 
            'milk_Status' => 'Thawing Completed'
        ]);

        return response()->json(['success' => true]);
    }

    public function saveStage3(Request $request, $id)
    {
        $request->validate(['bottles' => 'required|array|min:1']);
        
        $milk = Milk::findOrFail($id);

        // --- VALIDATION: Check Total Volume ---
        $totalInputVolume = collect($request->bottles)->sum('volume');

        if ($totalInputVolume > $milk->milk_volume) {
            return response()->json([
                'success' => false, 
                'message' => "Total pasteurized volume ($totalInputVolume ml) exceeds the raw milk volume ($milk->milk_volume ml)."
            ], 422);
        }
        // --------------------------------------

        $milk->postBottles()->delete();

        foreach ($request->bottles as $b) {
            PostBottle::create([
                'milk_ID' => $id,
                'post_bottle_code' => $b['bottle_id'],
                'post_volume' => $b['volume'], 
                'post_pasteurization_date' => $b['pasteurization_date'],
                'post_expiry_date' => $b['expiry_date'],
                'post_micro_status' => 'Pending'
            ]);
        }

        $milk->update([
            'milk_stage3StartDate' => now(),
            'milk_Status' => 'Pasteurization Completed'
        ]);

        return response()->json(['success' => true]);
    }

    public function saveStage4(Request $request, $id)
    {
        $request->validate(['bottles' => 'required|array']);

        foreach ($request->bottles as $b) {
            PostBottle::where('milk_ID', $id)
                ->where('post_bottle_code', $b['bottle_id'])
                ->update([
                    'post_micro_total_viable' => $b['total_viable'],
                    'post_micro_entero' => $b['entero'],
                    'post_micro_staph' => $b['staph'],
                    'post_micro_status' => $b['result'] // 'Contaminated' or 'Not Contaminated'
                ]);
        }

        $milk = Milk::findOrFail($id);
        $milk->update([
            'milk_stage4StartDate' => now(),
            'milk_Status' => 'Microbiology Completed'
        ]);

        return response()->json(['success' => true]);
    }

    public function saveStage5(Request $request, $id)
    {
        $request->validate([
            'bottles' => 'required|array',
            'drawer_id' => 'required|string'
        ]);

        foreach ($request->bottles as $b) {
            PostBottle::where('milk_ID', $id)
                ->where('post_bottle_code', $b['bottle_id'])
                ->update(['post_storage_location' => $request->drawer_id]);
        }

        $milk = Milk::findOrFail($id);
        $milk->update([
            'milk_stage5StartDate' => now(),
            'milk_Status' => 'Storage Completed'
        ]);

        return response()->json(['success' => true]);
    }

}
