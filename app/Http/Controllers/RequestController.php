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

    public function viewRequestDoctor()
    {
        $requests = MilkRequest::with(['parent', 'doctor'])->latest()->get();
        return view('doctor.doctor_milk-request', compact('requests'));
    }

    public function viewRequestNurse(Request $request)
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
        $milks = Milk::whereHas('postBottles', function($query) {
                    $query->whereDate('post_expiry_date', '>=', Carbon::today());
                })
                ->where('milk_Status', 'Storage Completed') // Use your updated status
                ->where('milk_shariahApproval', '1')
                ->with(['postBottles' => function($query) {
                    
                    $query->whereDate('post_expiry_date', '>=', Carbon::today());
                }])
                ->get();

        return view('nurse.nurse_milk-request-list', compact('requests', 'milks'));
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

    public function store(Request $request)
    {
        // 1. Validate the Incoming Data
        $validated = $request->validate([
            'pr_ID'           => 'required|exists:parent,pr_ID', // Ensure parent exists
            'weight'          => 'required|numeric|min:0.1',
            'entered_volume'  => 'required|numeric|min:1',
            'baby_age'        => 'required|integer|min:0',
            'age_unit'        => 'required|in:days,months',
            'gestational_age' => 'nullable|integer|min:20|max:42',
            'kinship_method'  => 'required|in:yes,no',
            'feeding_tube'    => 'nullable|string',
            'oral_feeding'    => 'nullable|string',
            'feeding_date'    => 'required|date',
            'start_time'      => 'required',
            'feeds_per_day'   => 'required|integer|min:1',
            'interval_hours'  => 'required|integer|min:1',
        ]);

        // 2. Get the logged-in Doctor's ID
        // Assumes your User model has a 'doctor' relationship: return $this->hasOne(Doctor::class);
        $doctorID = Auth::user()->doctor->dr_ID;

        // 3. Create the Record
        MilkRequest::create([
            'dr_ID'              => $doctorID,
            'pr_ID'              => $request->pr_ID,
            
            // Map Form Inputs to DB Columns
            'current_weight'     => $request->weight,
            'total_daily_volume' => $request->entered_volume,
            
            'baby_age'           => $request->baby_age,
            'age_unit'           => $request->age_unit,
            'gestational_age'    => $request->gestational_age,
            
            'kinship_method'     => $request->kinship_method,
            'feeding_tube'       => $request->feeding_tube,
            'oral_feeding'       => $request->oral_feeding,
            
            'feeding_start_date' => $request->feeding_date,
            'feeding_start_time' => $request->start_time,
            'feeding_perday'     => $request->feeds_per_day,
            'feeding_interval'   => $request->interval_hours,
            
            'status'             => 'Pending'
        ]);

        // 4. Return JSON Response (for your Fetch API)
        return response()->json([
            'success' => true,
            'message' => 'Milk request submitted successfully!'
        ]);
    }
    
    public function allocateMilk(Request $request)
    {
        $request->validate([
            'request_id'      => 'required|exists:request,request_ID',
            'selected_milk'   => 'required|array',
            'allocation_times'=> 'required|array',
            'total_volume'    => 'required',
            'storage_location'=> 'required'
        ]);

        foreach ($request->selected_milk as $milk) {
            Allocation::create([
                'request_ID'          => $request->request_id,
                'milk_ID'             => $milk['id'],
                'total_selected_milk' => $request->total_volume,
                'storage_location'    => $request->storage_location,
                
                // REMOVE json_encode HERE. Pass the array directly.
                // Eloquent will convert it to JSON because of the 'array' cast in Model.
                'allocation_milk_date_time' => [
                    'milk_id' => $milk['id'],
                    'datetime' => $request->allocation_times[$milk['id']] ?? null
                ]
            ]);
        }

        // Update request status to approved
        $milkRequest = MilkRequest::findOrFail($request->request_id);
        $milkRequest->status = 'Approved';
        $milkRequest->save();

        return response()->json(['success' => true]);
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