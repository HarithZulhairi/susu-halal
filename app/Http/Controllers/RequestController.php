<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentModel;
use App\Models\Request as MilkRequest;
use Illuminate\Support\Facades\Auth;
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
        // Assuming relationship is 'allocations' (plural) for a request having multiple allocated bottles
        $query = MilkRequest::with([
            'parent', 
            'doctor', 
            'allocations.milk' // Changed 'allocation' to 'allocations' (standard naming)
        ])->latest();

        // 2. Apply Filters
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

        // 3. Paginate & Persist Query Strings (FIXED)
        $requests = $query->paginate(10)->withQueryString(); 

        // 4. Transform Data for JavaScript
        $requests->getCollection()->transform(function ($req) {
    // ... (keep allocation mapping) ...

            $req->json_data = [
                // --- EXISTING FIELDS ---
                'patient_name'   => $req->parent->pr_BabyName ?? '-',
                'patient_dob'    => \Carbon\Carbon::parse($req->parent->pr_BabyDOB)->format('d-m-Y') ?? '-',
                'formatted_id'   => $req->parent->formattedID ?? '-',
                'cubicle'        => $req->parent->pr_NICU ?? '-',
                'parent_consent' => $req->parent->pr_ConsentStatus ?? 'Pending',
                'weight'         => $req->current_weight ?? 0,
                'age'            => $req->current_baby_age ?? '-',
                'gestational'    => $req->gestational_age ?? '-',
                'total_vol'      => $req->total_daily_volume,
                'date_requested' => $req->created_at->format('d-m-Y'),
                'feed_time'      => \Carbon\Carbon::parse($req->feeding_start_date . ' ' . $req->feeding_start_time)->format('d-m-Y H:i'),
                'feeds'          => $req->feeding_perday,
                'interval'       => $req->feeding_interval,
                'kinship_method' => $req->kinship_method,
                
                'volume_per_feed'=> $req->volume_per_feed,
                'drip_total'     => $req->drip_total,
                'oral_total'     => $req->oral_total,
                'oral_per_feed'  => $req->oral_per_feed,
                'tube_method'    => $req->feeding_tube,
                'oral_method'    => $req->oral_feeding,
                'allocated_items'=> $req->allocated_items, // (mapped previously)

                // --- NEW FIELDS ADDED FOR FULL VIEW ---
                'status'         => $req->status,
                'doctor_name'    => $req->doctor->dr_Name ?? 'Unknown',
                'allergy_info'   => $req->parent->pr_Allergy ?? 'None',
            ];

            return $req;
        });

        // 5. Get Available Milk
        $milks = Milk::where('milk_Status', 'Storage Completed')
            ->where('milk_shariahApproval', 1)
            ->whereHas('postBottles', function ($q) {
                $q->whereDate('post_expiry_date', '>=', Carbon::today());
            })
            ->get();

        $postbottles = PostBottle::whereDate('post_expiry_date', '>=', Carbon::today())
            ->where('post_micro_status', 'NOT CONTAMINATED')
            ->whereHas('milk', function ($q) {
                $q->where('milk_Status', 'Storage Completed')
                  ->where('milk_shariahApproval', 1);
            })
            ->get();

        return view('nurse.nurse_milk-request-list', compact('requests', 'milks', 'postbottles'));
    }


    public function viewRequestHMMC(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status'); // Get status from tabs

        // 1. Start the query
        $query = MilkRequest::with(['parent', 'doctor', 'allocation.milk']);

        // 2. Apply Search Filter
        if ($search) {
            $query->whereHas('parent', function ($q) use ($search) {
                $q->where('pr_BabyName', 'LIKE', "%{$search}%")
                ->orWhere('pr_ID', 'LIKE', "%{$search}%");
            });
        }

        // 3. Apply Status Filter (Tabs)
        if ($status && $status !== 'All') {
            $query->where('status', $status);
        }

        // 4. Order and Paginate
        $requests = $query->latest()->paginate(10);

        // 5. Append query parameters so tabs + search + pagination work together
        $requests->appends(['search' => $search, 'status' => $status]);

        // Only NON-EXPIRED milk
        $milks = Milk::whereDate('milk_expiryDate', '>=', Carbon::today())
                    ->where('milk_Status', 'Distributing Completed')
                    ->where('milk_shariahApproval', '1')
                    ->get();

        return view('hmmc.hmmc_milk-request', compact('requests', 'milks'));
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

        return response()->json(['success' => true, 'message' => 'Submitted successfully!']);
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

    //Infant Weight Record//

    public function viewInfantWeightHMMC(Request $request)
    {
        // Start Query with Relationships
        $query = ParentModel::with(['requests.allocation.milk']);

        // --- SEARCH LOGIC ---
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('pr_ID', 'like', "%{$searchTerm}%")
                ->orWhere('pr_BabyName', 'like', "%{$searchTerm}%");
            });
        }

        // --- PAGINATION CHANGE ---
        // Change ->get() to ->paginate(10)
        $parents = $query->latest()->paginate(10);

        // Ensure search term persists when clicking "Next Page"
        $parents->appends(['search' => $request->search]);

        return view('hmmc.hmmc_list-of-infants', compact('parents'));
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
                'requests.allocation.milk'
            ])
            ->firstOrFail();

        return view('parent.parent_my-infant-request', compact('parent'));
    }

    public function viewInfantMilkShariah(Request $request)
    {
        // Same as HMMC
        $query = ParentModel::with(['requests.allocation.milk']);

        // Search logic
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('pr_ID', 'like', "%{$request->search}%")
                ->orWhere('pr_BabyName', 'like', "%{$request->search}%");
            });
        }

        // Pagination
        $parents = $query->latest()->paginate(10);
        $parents->appends(['search' => $request->search]);

        return view('shariah.shariah_infant-request', compact('parents'));
    }




}