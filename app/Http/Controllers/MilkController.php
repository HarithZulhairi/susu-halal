<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donor;
use App\Models\Milk;
use Carbon\Carbon;

class MilkController extends Controller
{
    // MilkController.php
    public function viewMilkLabtech(Request $request)
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

        return view('labtech.labtech_manage-milk-records', compact('donors', 'milks'));
    }

    public function viewMilkNurse(Request $request)
    {
        $donors = Donor::all();
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

        $milks = $query->orderBy('created_at', 'desc')->get();

        return view('nurse.nurse_manage-milk-records', compact('milks'));
    }

    public function viewMilkShariah(Request $request)
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
        // 1. Fetch the Milk record with its Donor
        $milk = Milk::with('donor')->findOrFail($id);

        // 2. Decode the JSON result from Screening Stage (milk_stage1Result)
        // The database stores it as a JSON string or null.
        $screeningResults = [];
        if ($milk->milk_stage1Result) {
            $decoded = json_decode($milk->milk_stage1Result, true);
            if (is_array($decoded)) {
                $screeningResults = $decoded;
            }
        }

        // 3. Return view with data
        return view('shariah.shariah_view-milk-processing', compact('milk', 'screeningResults'));
    }

    public function updateDecision(Request $request)
    {
        $request->validate([
            'milk_id' => 'required|exists:milk,milk_ID',
            'approval' => 'required|boolean',
            'remarks' => 'nullable|string'
        ]);

        $milk = Milk::findOrFail($request->milk_id);
        $milk->milk_shariahApproval = $request->approval;
        $milk->milk_shariahRemarks = $request->remarks;
        $milk->milk_shariahApprovalDate = Carbon::now(); // Set current date
        $milk->save();

        return response()->json(['success' => true]);
    }

    // 2. Update Method
    public function updateMilkRecordHMMC(Request $request, $id)
    {
        $request->validate([
            'milk_volume' => 'required|numeric|min:0',
            'milk_expiryDate' => 'required|date',
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
        $request->validate([
            'dn_ID'          => 'required|exists:donor,dn_ID',
            'milk_volume'    => 'required|numeric|min:0.1',
            'milk_expiryDate'=> 'required|date|after:yesterday',
        ]);

        Milk::create([
            'dn_ID'               => $request->dn_ID,
            'pr_ID'               => null, // will be assigned later when given to a baby/parent

            'milk_volume'         => $request->milk_volume,
            'milk_expiryDate'     => $request->milk_expiryDate,

            // New simplified fields
            'milk_shariahApproval' => null, // or null if you prefer, but false is clearer
            'milk_shariahApprovalDate' => null,
            'milk_shariahRemarks' => null,
            'milk_Status'         => null, // Overall status

            // Stage 1: Screening (starts immediately when milk is received)
            'milk_stage1StartDate' => null,
            'milk_stage1EndDate'   => null,
            'milk_stage1StartTime'   => null,
            'milk_stage1EndTime'   => null,
            'milk_stage1Result'    => null,

            // Stage 2: Processing (Homogenization + Pasteurization)
            'milk_stage2StartDate' => null,
            'milk_stage2EndDate'   => null,
            'milk_stage2StartTime'   => null,
            'milk_stage2EndTime'   => null,

            // Stage 3: Labelling & Storage
            'milk_stage3StartDate' => null,
            'milk_stage3EndDate'   => null,
            'milk_stage3StartTime'   => null,
            'milk_stage3EndTime'   => null,
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
        // Load donor + any related data
        $milk->load('donor');

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

    // AJAX endpoint to mark labelling complete
    public function markLabellingComplete(Request $request, Milk $milk)
    {
        $milk->update(['milk_Status' => 'Labelling Completed']);
        return response()->json(['success' => true]);
    }

    // AJAX endpoint to mark distributing complete
    public function markDistributingComplete(Request $request, Milk $milk)
    {
        $milk->update(['milk_Status' => 'Distributing Completed']);
        return response()->json(['success' => true]);
    }

    // AJAX endpoint to mark labelling in-progress (stage 2 started but not completed)
    public function markLabellingInProgress(Request $request, Milk $milk)
    {
        $milk->update(['milk_Status' => 'Labelling']);
        return response()->json(['success' => true]);
    }

    // AJAX endpoint to mark distributing in-progress (stage 3 started but not completed)
    public function markDistributingInProgress(Request $request, Milk $milk)
    {
        $milk->update(['milk_Status' => 'Distributing']);
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

}
