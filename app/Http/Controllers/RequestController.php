<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentModel;
use App\Models\Request as MilkRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Model\Doctor;
use App\Models\Milk;
use App\Models\PostBottle;
use App\Models\PreBottle;
use App\Models\Allocation;
use Carbon\Carbon;

class RequestController extends Controller
{
    public function create()
    {
        $parents = ParentModel::all();

        return view('doctor.doctor_milk-request-form', compact('parents'));
    }

    public function viewRequestDoctor(Request $request)
    {
        // Start with Eager Loading
        $query = MilkRequest::with(['parent', 'doctor'])->latest();

        // --- 1. Search Filter (Patient Name or ID) ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('parent', function($q) use ($search) {
                $q->where('pr_BabyName', 'like', "%{$search}%")
                ->orWhere('pr_ID', 'like', "%{$search}%"); // Assuming ID is searchable
            });
        }

        // --- 2. Request Status Filter ---
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // --- 3. Volume Range Filter ---
        if ($request->filled('vol_min')) {
            $query->where('total_daily_volume', '>=', $request->vol_min);
        }
        if ($request->filled('vol_max')) {
            $query->where('total_daily_volume', '<=', $request->vol_max);
        }

        // --- 4. Date Requested Range ---
        if ($request->filled('req_date_from')) {
            $query->whereDate('created_at', '>=', $request->req_date_from);
        }
        if ($request->filled('req_date_to')) {
            $query->whereDate('created_at', '<=', $request->req_date_to);
        }

        // --- 5. Feeding Date Range ---
        if ($request->filled('feed_date_from')) {
            $query->whereDate('feeding_start_date', '>=', $request->feed_date_from);
        }
        if ($request->filled('feed_date_to')) {
            $query->whereDate('feeding_start_date', '<=', $request->feed_date_to);
        }

        // Pagination: 10 per page, appending query params so filters persist across pages
        $requests = $query->paginate(10)->withQueryString();

        return view('doctor.doctor_milk-request', compact('requests'));
    }

    public function viewRequestNurse(Request $request)
    {
        // 1. Base Query with Eager Loading
        $query = MilkRequest::with([
            'parent', 
            'doctor', 
            'allocations.postBottles',
            'allocations.feedRecords.nurse' 
        ]);

        // 2. Apply Filters (Keep your existing filter logic)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('parent', function($q) use ($search) {
                $q->where('pr_BabyName', 'like', "%{$search}%")
                ->orWhere('formattedID', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }
        if ($request->filled('vol_min')) {
            $query->where('total_daily_volume', '>=', $request->vol_min);
        }
        if ($request->filled('vol_max')) {
            $query->where('total_daily_volume', '<=', $request->vol_max);
        }
        if ($request->filled('req_date_from')) {
            $query->whereDate('created_at', '>=', $request->req_date_from);
        }
        if ($request->filled('req_date_to')) {
            $query->whereDate('created_at', '<=', $request->req_date_to);
        }
        if ($request->filled('feed_date_from')) {
            $query->whereDate('feeding_start_date', '>=', $request->feed_date_from);
        }
        if ($request->filled('feed_date_to')) {
            $query->whereDate('feeding_start_date', '<=', $request->feed_date_to);
        }

        // 3. Paginate & Persist Query Strings
        $requests = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString(); 

        // 5. Get Available Milk
        $milks = Milk::where('milk_Status', 'Storage Completed')
            ->where('milk_shariahApproval', 1)
            ->whereHas('postBottles', function ($q) {
                $q->whereDate('post_expiry_date', '>=', \Carbon\Carbon::today());
            })
            ->get();

        $postbottles = PostBottle::whereDate('post_expiry_date', '>=', \Carbon\Carbon::today())
            ->where('post_micro_status', 'NOT CONTAMINATED')
            ->whereDoesntHave('allocations')
            ->whereHas('milk', function ($q) {
                $q->where('milk_Status', 'Storage Completed')
                ->where('milk_shariahApproval', 1);
            })
            ->get();
        
        // Pass allocations variable explicitly if needed by other views, though JS uses json_data
        $allocations = Allocation::all(); 

        return view('nurse.nurse_milk-request-list', compact('requests', 'milks', 'postbottles', 'allocations'));
    }

    public function viewRequestHMMC(Request $request)
    {
        // 1. Base Query with Eager Loading
        $query = MilkRequest::with([
            'parent', 
            'doctor', 
            'allocations.postBottles',
            'allocations.feedRecords.nurse' 
        ]);

        // 2. Apply Filters (Keep your existing filter logic)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('parent', function($q) use ($search) {
                $q->where('pr_BabyName', 'like', "%{$search}%")
                ->orWhere('formattedID', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }
        if ($request->filled('vol_min')) {
            $query->where('total_daily_volume', '>=', $request->vol_min);
        }
        if ($request->filled('vol_max')) {
            $query->where('total_daily_volume', '<=', $request->vol_max);
        }
        if ($request->filled('req_date_from')) {
            $query->whereDate('created_at', '>=', $request->req_date_from);
        }
        if ($request->filled('req_date_to')) {
            $query->whereDate('created_at', '<=', $request->req_date_to);
        }
        if ($request->filled('feed_date_from')) {
            $query->whereDate('feeding_start_date', '>=', $request->feed_date_from);
        }
        if ($request->filled('feed_date_to')) {
            $query->whereDate('feeding_start_date', '<=', $request->feed_date_to);
        }

        // 3. Paginate & Persist Query Strings
        $requests = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString(); 

        // 5. Get Available Milk
        $milks = Milk::where('milk_Status', 'Storage Completed')
            ->where('milk_shariahApproval', 1)
            ->whereHas('postBottles', function ($q) {
                $q->whereDate('post_expiry_date', '>=', \Carbon\Carbon::today());
            })
            ->get();

        $postbottles = PostBottle::whereDate('post_expiry_date', '>=', \Carbon\Carbon::today())
            ->where('post_micro_status', 'NOT CONTAMINATED')
            ->whereDoesntHave('allocations')
            ->whereHas('milk', function ($q) {
                $q->where('milk_Status', 'Storage Completed')
                ->where('milk_shariahApproval', 1);
            })
            ->get();
        
        // Pass allocations variable explicitly if needed by other views, though JS uses json_data
        $allocations = Allocation::all(); 

        return view('hmmc.hmmc_milk-request', compact('requests', 'milks', 'postbottles', 'allocations'));
    }

    public function viewRequestShariah(Request $request)
    {
        // 1. Base Query with Eager Loading
        $query = MilkRequest::with([
            'parent', 
            'doctor', 
            'allocations.postBottles',
            'allocations.feedRecords.nurse' 
        ]);

        // 2. Apply Filters (Keep your existing filter logic)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('parent', function($q) use ($search) {
                $q->where('pr_BabyName', 'like', "%{$search}%")
                ->orWhere('formattedID', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }
        if ($request->filled('vol_min')) {
            $query->where('total_daily_volume', '>=', $request->vol_min);
        }
        if ($request->filled('vol_max')) {
            $query->where('total_daily_volume', '<=', $request->vol_max);
        }
        if ($request->filled('req_date_from')) {
            $query->whereDate('created_at', '>=', $request->req_date_from);
        }
        if ($request->filled('req_date_to')) {
            $query->whereDate('created_at', '<=', $request->req_date_to);
        }
        if ($request->filled('feed_date_from')) {
            $query->whereDate('feeding_start_date', '>=', $request->feed_date_from);
        }
        if ($request->filled('feed_date_to')) {
            $query->whereDate('feeding_start_date', '<=', $request->feed_date_to);
        }

        // 3. Paginate & Persist Query Strings
        $requests = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString(); 

        // 5. Get Available Milk
        $milks = Milk::where('milk_Status', 'Storage Completed')
            ->where('milk_shariahApproval', 1)
            ->whereHas('postBottles', function ($q) {
                $q->whereDate('post_expiry_date', '>=', \Carbon\Carbon::today());
            })
            ->get();

        $postbottles = PostBottle::whereDate('post_expiry_date', '>=', \Carbon\Carbon::today())
            ->where('post_micro_status', 'NOT CONTAMINATED')
            ->whereDoesntHave('allocations')
            ->whereHas('milk', function ($q) {
                $q->where('milk_Status', 'Storage Completed')
                ->where('milk_shariahApproval', 1);
            })
            ->get();
        
        // Pass allocations variable explicitly if needed by other views, though JS uses json_data
        $allocations = Allocation::all(); 

        return view('shariah.shariah_milk-request', compact('requests', 'milks', 'postbottles', 'allocations'));
    }

    //CRUD other than viewing//
    public function store(Request $request)
    {
        $request->validate([
            'pr_ID'              => 'required|exists:parent,pr_ID',
            'weight'             => 'required|numeric',
            'entered_volume'     => 'required|numeric',
            'baby_age'           => 'required|string', // Validating the string format
            'gestational_age'    => 'nullable|integer',
            'kinship_method'     => 'required',
            'feeding_date'       => 'required|date',
            'start_time'         => 'required',
        ]);

        MilkRequest::create([
            'dr_ID'              => auth()->user()->doctor->dr_ID,
            'pr_ID'              => $request->pr_ID,
            'current_weight'     => $request->weight,
            'total_daily_volume' => $request->entered_volume,
            'current_baby_age'   => $request->baby_age, // "0 years 2 months 5 days"
            'gestational_age'    => $request->gestational_age,
            'kinship_method'     => $request->kinship_method,
            'volume_per_feed'    => $request->volume_per_feed,
            'drip_total'         => $request->drip_total,
            'oral_total'         => $request->oral_total,
            'oral_per_feed'      => $request->oral_per_feed,
            'feeding_tube'       => $request->feeding_tube,
            'oral_feeding'       => $request->oral_feeding,
            'feeding_start_date' => $request->feeding_date,
            'feeding_start_time' => $request->start_time,
            'feeding_perday'     => $request->feeds_per_day,
            'feeding_interval'   => $request->interval_hours,
            'status'             => 'Waiting'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Milk Request submitted successfully!'
        ]);
    }
    
public function allocateMilk(Request $request)
{
    \Log::info('🔥 ALLOCATE MILK HIT', $request->all());

    $request->validate([
        'request_id'              => 'required|exists:request,request_ID',
        'selected_milk'           => 'required|array|min:1',
        'selected_milk.*.id'      => 'required|exists:post_bottles,id',
        'selected_milk.*.volume'  => 'required|numeric|min:1',
        'storage_location'        => 'required|string',
    ]);


    DB::transaction(function () use ($request) {

        // 1. Get the milk request + patient
        $milkRequest = MilkRequest::with('parent')
            ->where('request_ID', $request->request_id)
            ->firstOrFail();

        $patientId = $milkRequest->pr_ID; // 👈 THIS is the receiver

        foreach ($request->selected_milk as $bottle) {

            // 2. Create allocation record
            Allocation::create([
                'request_ID'       => $milkRequest->request_ID,
                'postBottle_ID'    => $bottle['id'],
                'allocated_volume' => $bottle['volume'],
                'storage_location' => $request->storage_location,
                'allocated_at'     => now(),
            ]);

            // 3. Assign bottle to patient + lock it
            PostBottle::where('id', $bottle['id'])->update([
                'pr_ID'                => $patientId,
                'post_storage_location'=> $request->storage_location,
            ]);
        }

        // 4. Update request status
        $milkRequest->update([
            'status' => 'Approved'
        ]);
    });

    return response()->json([
        'success' => true,
        'message' => 'Milk allocated and assigned to patient successfully'
    ]);
}

    public function delete($id)
    {
        $req = MilkRequest::findOrFail($id);
        $req->delete();

        return response()->json([
            'success' => true,
            'message' => 'Milk request deleted successfully.'
        ]);
    }

    public function deleteAllocation(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:request,request_ID',
        ]);

        // 1. Delete all allocation records associated with this request ID
        Allocation::where('request_ID', $request->request_id)->delete();

        // 2. Find the Milk Request and revert status to 'Waiting'
        $milkRequest = MilkRequest::findOrFail($request->request_id);
        $milkRequest->status = 'Waiting';
        $milkRequest->save();

        return response()->json([
            'success' => true, 
            'message' => 'Allocation reverted successfully.'
        ]);
    }

public function dispenseMilk(Request $request)
{
    \Log::info('🧪 DISPENSE REQUEST', $request->all());

    $request->validate([
        'items' => 'required|array|min:1',
        'items.*.allocation_id' => 'required|exists:allocation,allocation_ID',
    ]);

    $dispensedCount = 0;

    DB::transaction(function () use ($request, &$dispensedCount) {

        foreach ($request->items as $item) {

            $allocation = Allocation::where('allocation_ID', $item['allocation_id'])
                ->whereNull('dispensed_at')
                ->firstOrFail();

            $allocation->update([
                'dispensed_at' => now(),
                'dispensed_by' => auth()->id(),
            ]);

            $dispensedCount++;
        }
    });

    return response()->json([
        'success' => true,
        'message' => "{$dispensedCount} bottles dispensed successfully."
    ]);
}





    //Infant Weight Record//

    public function viewInfantHMMC(Request $request)
    {
        $parents = ParentModel::all();
        return view('hmmc.hmmc_infant-list', compact('parents'));
    }

    public function setInfantWeightNurse()
    {
        $parents = ParentModel::all();
        return view('nurse.nurse_set-infant-weight', compact('parents'));
    }
    

    public function updateInfantWeightNurse(Request $request)
    {
        $request->validate([
            'pr_ID' => 'required|exists:parent,pr_ID',
            'pr_BabyCurrentWeight' => 'required|numeric|min:0',
        ]);

        $parent = ParentModel::findOrFail($request->pr_ID);
        $parent->pr_BabyCurrentWeight = $request->pr_BabyCurrentWeight;
        $parent->save();

        return response()->json(['success' => true, 'message' => 'Weight updated!']);
    }

    public function updateInfantWeightHMMC(Request $request)
    {
        $request->validate([
            'pr_ID' => 'required|exists:parent,pr_ID',
            'pr_BabyCurrentWeight' => 'required|numeric|min:0',
        ]);

        $parent = ParentModel::findOrFail($request->pr_ID);
        $parent->pr_BabyCurrentWeight = $request->pr_BabyCurrentWeight;
        $parent->save();

        return response()->json(['success' => true, 'message' => 'Weight updated!']);
    }

    public function viewMyInfantMilkRequests()
    {
        // Logged-in parent (user_id foreign key)
        $parent = ParentModel::where('user_id', auth()->id())
            ->with([
                'requests.doctor',
                'requests.allocations'
            ])
            ->firstOrFail();

        return view('parent.parent_my-infant-request', compact('parent'));
    }

    public function generateMilkReport($pr_ID)
    {
        $patient = ParentModel::findOrFail($pr_ID);
        
        // Calculate gestational age or use stored value
        $gestationalAge = $patient->pr_BabyGestationalAge ?? 'N/A';
        // If not stored, calculate from DOB if available (simplified logic)
        if ($gestationalAge === 'N/A' && $patient->pr_BabyDOB) {
            $dob = Carbon::parse($patient->pr_BabyDOB);
            $now = Carbon::now();
            $ageInWeeks = $dob->diffInWeeks($now);
            // Assuming 40 weeks is full term, this is just postnatal age in weeks
             $gestationalAge = $ageInWeeks . " (Postnatal)"; 
        }

        $reportData = [];
        $totalVolume = 0;

        // Fetch all allocations for this parent's requests
        $requests = MilkRequest::where('pr_ID', $pr_ID)
            ->with(['allocations.postBottles.milk', 'allocations.nurse', 'doctor'])
            ->get();

        foreach ($requests as $req) {
            foreach ($req->allocations as $alloc) {
                // Formatting data for the report view
                $milk = $alloc->postBottles->milk ?? null;
                $donorId = $milk ? '#D' . $milk->dn_ID : '-';
                
                $reportData[] = (object)[
                    'date' => $alloc->created_at->format('d/m/Y'),
                    'time' => $alloc->created_at->format('H:i'),
                    'batch_no' => $alloc->post_ID ? 'B' . $alloc->post_ID : '-',
                    'donor_id' => $donorId,
                    'consent_kinship' => $req->kinship_method,
                    'freq' => $req->feeding_interval ? 'Every ' . $req->feeding_interval . 'h' : '-',
                    'amount' => $alloc->total_selected_milk,
                    'incharge' => optional($alloc->nurse)->ns_Name ?? '-',
                    'witness' => '-', // Witness logic not in DB yet
                    'remark' => $req->kinship_method
                ];

                $totalVolume += $alloc->total_selected_milk;
            }
        }

        $generatedDate = now()->format('d M Y');

        return view('layouts.milk_report_pdf', compact('patient', 'reportData', 'totalVolume', 'generatedDate', 'gestationalAge'));
    }

}