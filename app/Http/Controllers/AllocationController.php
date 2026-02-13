<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Allocation;
use App\Models\FeedRecord;
use App\Models\Nurse;
use App\Models\Donor;
use App\Models\ParentModel;
use App\Models\Request as MilkRequest; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Import Auth
use Carbon\Carbon;


class AllocationController extends Controller
{
    public function viewTraceabilityNurse(Request $request)
    {

        $requestQuery = MilkRequest::whereIn('status', ['Allocated', 'Fully Dispensed']);

        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $requestQuery->whereHas('parent', function($q) use ($searchTerm) {
                $q->where('pr_BabyName', 'like', "%{$searchTerm}%")
                ->orWhere('pr_ID', 'like', "%{$searchTerm}%");
            });
        }

        $parentIDs = $requestQuery->pluck('pr_ID')->unique();
        
        // Manual Pagination for the array of IDs
        $page = $request->input('page', 1);
        $perPage = 10;
        $paginatedParentIDs = new \Illuminate\Pagination\LengthAwarePaginator(
            $parentIDs->forPage($page, $perPage),
            $parentIDs->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $infants = [];
        
        $visibleRequests = MilkRequest::whereIn('status', ['Allocated', 'Fully Dispensed'])
            ->whereIn('pr_ID', $paginatedParentIDs->items()) // Filter by the paginated IDs
            ->with([
                'parent', 
                'doctor',
                'allocations.postBottles.milk.donor', 
                'allocations.nurse'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('parent.pr_ID');

        foreach ($visibleRequests as $patientID => $patientRequests) {
            $firstReq = $patientRequests->first(); 
            $parent = $firstReq->parent;

            // Build the infant object structure
            $infantObj = (object)[
                'id' => $parent->formattedID ?? 'ID-'.$parent->pr_ID,
                'raw_id' => $parent->pr_ID,
                'name' => $parent->pr_BabyName,
                'nicu' => $parent->pr_NICU,
                'last_updated' => $patientRequests->max('updated_at')->format('d/m/Y • h:i A'),
                'current_weight' => $firstReq->current_weight,
                'baby_gender' => $parent->pr_BabyGender,
                'status' => $firstReq->status,
                'requests' => []
            ];

            // Process each request for this infant
            foreach ($patientRequests as $req) {
                
                // Map Allocations
                $allocationList = $req->allocations->map(function($alloc) {
                    return (object)[
                        'milk_id'    => $alloc->postBottles ? $alloc->postBottles->post_bottle_code : 'N/A',
                        'volume'     => $alloc->total_selected_milk,
                        'time'       => $alloc->created_at->format('Y-m-d h:i A'),
                        'nurse_id'   => $alloc->nurse ? '#N'.$alloc->nurse->ns_ID : '-',
                        'nurse_name' => $alloc->nurse ? $alloc->nurse->ns_Name : 'Unknown'
                    ];
                });

                // Get Donor Info
                $firstAlloc = $req->allocations->first();
                $donor = $firstAlloc && $firstAlloc->postBottles && $firstAlloc->postBottles->milk && $firstAlloc->postBottles->milk->donor 
                    ? $firstAlloc->postBottles->milk->donor 
                    : null;

                // Build the Request Detail Object
                $infantObj->requests[] = (object)[
                    'req_id' => $req->request_ID,
                    'total_allocated_vol' => $req->allocations->sum('total_selected_milk'),
                    'details' => (object)[
                        'patient_name' => $parent->pr_BabyName,
                        'patient_id'   => $parent->formattedID ?? '#P'.$parent->pr_ID,
                        'patient_nicu' => $parent->pr_NICU,
                        'parent_consent' => $parent->pr_ConsentStatus ?? 'N/A',
                        'donor_id'     => $donor ? '#D'.$donor->dn_ID : 'Mixed/Unknown',
                        'donor_name'   => $donor ? $donor->dn_FullName : 'Multiple/Unknown',
                        'consent'      => 'Consent Granted',
                        'method'       => $req->kinship_method === 'yes' ? 'Milk Kinship' : 'No Milk Kinship',
                        'schedule'     => $req->feeding_perday . ' feeds/day (' . $req->feeding_interval . 'h interval)',
                        'start_time'   => $req->feeding_start_time,
                        'doctor_id'    => $req->doctor ? '#Dr'.$req->doctor->dr_ID : '-',
                        'doctor_name'  => $req->doctor ? $req->doctor->dr_Name : 'Unknown',
                        'status'       => $req->status,
                        'direct_oral'  => $req->oral_feeding,
                        'feeding_tube' => $req->feeding_tube,
                        'oral_volume'  => $req->oral_total . ' ml',
                        'tube_volume'  => $req->drip_total . ' ml',
                        'allocations'  => $allocationList
                    ]
                ];
            }
            $infants[] = $infantObj;
        }

        return view('nurse.nurse_infants-request', ['infants' => $infants, 'requests' => $paginatedParentIDs]);
    }

    public function viewTraceabilityDoctor(Request $request)
    {

        $requestQuery = MilkRequest::whereIn('status', ['Allocated', 'Fully Dispensed']);

        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $requestQuery->whereHas('parent', function($q) use ($searchTerm) {
                $q->where('pr_BabyName', 'like', "%{$searchTerm}%")
                ->orWhere('pr_ID', 'like', "%{$searchTerm}%");
            });
        }

        $parentIDs = $requestQuery->pluck('pr_ID')->unique();
        
        // Manual Pagination for the array of IDs
        $page = $request->input('page', 1);
        $perPage = 10;
        $paginatedParentIDs = new \Illuminate\Pagination\LengthAwarePaginator(
            $parentIDs->forPage($page, $perPage),
            $parentIDs->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $infants = [];
        
        $visibleRequests = MilkRequest::whereIn('status', ['Allocated', 'Fully Dispensed'])
            ->whereIn('pr_ID', $paginatedParentIDs->items()) // Filter by the paginated IDs
            ->with([
                'parent', 
                'doctor',
                'allocations.postBottles.milk.donor', 
                'allocations.nurse'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('parent.pr_ID');

        foreach ($visibleRequests as $patientID => $patientRequests) {
            $firstReq = $patientRequests->first(); 
            $parent = $firstReq->parent;

            // Build the infant object structure
            $infantObj = (object)[
                'id' => $parent->formattedID ?? 'ID-'.$parent->pr_ID,
                'raw_id' => $parent->pr_ID,
                'name' => $parent->pr_BabyName,
                'nicu' => $parent->pr_NICU,
                'last_updated' => $patientRequests->max('updated_at')->format('d/m/Y • h:i A'),
                'current_weight' => $firstReq->current_weight,
                'baby_gender' => $parent->pr_BabyGender,
                'status' => $firstReq->status,
                'requests' => []
            ];

            // Process each request for this infant
            foreach ($patientRequests as $req) {
                
                // Map Allocations
                $allocationList = $req->allocations->map(function($alloc) {
                    return (object)[
                        'milk_id'    => $alloc->postBottles ? $alloc->postBottles->post_bottle_code : 'N/A',
                        'volume'     => $alloc->total_selected_milk,
                        'time'       => $alloc->created_at->format('Y-m-d h:i A'),
                        'nurse_id'   => $alloc->nurse ? '#N'.$alloc->nurse->ns_ID : '-',
                        'nurse_name' => $alloc->nurse ? $alloc->nurse->ns_Name : 'Unknown'
                    ];
                });

                // Get Donor Info
                $firstAlloc = $req->allocations->first();
                $donor = $firstAlloc && $firstAlloc->postBottles && $firstAlloc->postBottles->milk && $firstAlloc->postBottles->milk->donor 
                    ? $firstAlloc->postBottles->milk->donor 
                    : null;

                // Build the Request Detail Object
                $infantObj->requests[] = (object)[
                    'req_id' => $req->request_ID,
                    'total_allocated_vol' => $req->allocations->sum('total_selected_milk'),
                    'details' => (object)[
                        'patient_name' => $parent->pr_BabyName,
                        'patient_id'   => $parent->formattedID ?? '#P'.$parent->pr_ID,
                        'patient_nicu' => $parent->pr_NICU,
                        'parent_consent' => $parent->pr_ConsentStatus ?? 'N/A',
                        'donor_id'     => $donor ? '#D'.$donor->dn_ID : 'Mixed/Unknown',
                        'donor_name'   => $donor ? $donor->dn_FullName : 'Multiple/Unknown',
                        'consent'      => 'Consent Granted',
                        'method'       => $req->kinship_method === 'yes' ? 'Milk Kinship' : 'No Milk Kinship',
                        'schedule'     => $req->feeding_perday . ' feeds/day (' . $req->feeding_interval . 'h interval)',
                        'start_time'   => $req->feeding_start_time,
                        'doctor_id'    => $req->doctor ? '#Dr'.$req->doctor->dr_ID : '-',
                        'doctor_name'  => $req->doctor ? $req->doctor->dr_Name : 'Unknown',
                        'status'       => $req->status,
                        'direct_oral'  => $req->oral_feeding,
                        'feeding_tube' => $req->feeding_tube,
                        'oral_volume'  => $req->oral_total . ' ml',
                        'tube_volume'  => $req->drip_total . ' ml',
                        'allocations'  => $allocationList
                    ]
                ];
            }
            $infants[] = $infantObj;
        }

        return view('doctor.doctor_infants-request', ['infants' => $infants, 'requests' => $paginatedParentIDs]);
    }

    public function viewTraceabilityDonor(Request $request)
    {
        // 1. Get the Logged-in Donor's ID (Integer)
        // Check if we are using a specific guard or linking to the User table
        $user = auth()->user(); 
        $donorProfile = Donor::where('user_id', $user->id)->first();
        
        if (!$donorProfile) {
            abort(403, 'Donor profile not found.');
        }
        
        $donorId = $donorProfile->dn_ID; // EXTRACT THE ID INTEGER

        // 2. Base Query: Only requests containing this donor's milk
        $requestQuery = MilkRequest::whereIn('status', ['Allocated', 'Fully Dispensed'])
            ->whereHas('allocations.postBottles.milk', function($q) use ($donorId) {
                $q->where('dn_ID', $donorId);
            });

        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $requestQuery->whereHas('parent', function($q) use ($searchTerm) {
                $q->where('pr_BabyName', 'like', "%{$searchTerm}%")
                ->orWhere('pr_ID', 'like', "%{$searchTerm}%");
            });
        }

        // 3. Paginate Parents
        $parentIDs = $requestQuery->pluck('pr_ID')->unique();
        
        $page = $request->input('page', 1);
        $perPage = 10;
        $paginatedParentIDs = new \Illuminate\Pagination\LengthAwarePaginator(
            $parentIDs->forPage($page, $perPage),
            $parentIDs->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // 4. Fetch Data with Strict Filtering
        $visibleRequests = MilkRequest::whereIn('status', ['Allocated', 'Fully Dispensed'])
            ->whereIn('pr_ID', $paginatedParentIDs->items())
            ->whereHas('allocations.postBottles.milk', function($q) use ($donorId) {
                $q->where('dn_ID', $donorId);
            })
            ->with([
                'parent', 
                'doctor',
                // STRICTLY FILTER Allocations: Only load allocations belonging to this donor
                'allocations' => function($q) use ($donorId) {
                    $q->whereHas('postBottles.milk', function($sq) use ($donorId) {
                        $sq->where('dn_ID', $donorId);
                    });
                },
                // Load nested relationships for the filtered allocations
                'allocations.postBottles.milk.donor', 
                'allocations.nurse'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('parent.pr_ID');

        $infants = [];

        foreach ($visibleRequests as $patientID => $patientRequests) {
            $firstReq = $patientRequests->first(); 
            $parent = $firstReq->parent;

            $infantObj = (object)[
                'id' => $parent->formattedID ?? 'ID-'.$parent->pr_ID,
                'raw_id' => $parent->pr_ID, // Important for PDF link
                'name' => $parent->pr_BabyName,
                'nicu' => $parent->pr_NICU,
                'last_updated' => $patientRequests->max('updated_at')->format('d/m/Y • h:i A'),
                'current_weight' => $firstReq->current_weight,
                'baby_gender' => $parent->pr_BabyGender,
                'status' => $firstReq->status,
                'requests' => []
            ];

            foreach ($patientRequests as $req) {
                
                // Because we filtered the 'allocations' relation in the query above,
                // $req->allocations only contains THIS donor's bottles.
                $donorVolume = $req->allocations->sum('total_selected_milk');

                // Safety check
                if ($donorVolume <= 0) continue;

                $allocationList = $req->allocations->map(function($alloc) {
                    return (object)[
                        'milk_id'    => $alloc->postBottles ? $alloc->postBottles->post_bottle_code : 'N/A',
                        'volume'     => $alloc->total_selected_milk,
                        'time'       => $alloc->created_at->format('Y-m-d h:i A'),
                        'nurse_id'   => $alloc->nurse ? '#N'.$alloc->nurse->ns_ID : '-',
                        'nurse_name' => $alloc->nurse ? $alloc->nurse->ns_Name : 'Unknown'
                    ];
                });

                // Set Donor Info (Always the logged-in user)
                $donorName = $donorProfile->dn_FullName;
                $donorIDStr = '#D'.$donorProfile->dn_ID;

                $infantObj->requests[] = (object)[
                    'req_id' => $req->request_ID,
                    'total_allocated_vol' => $donorVolume, // Shows ONLY volume from this donor
                    'details' => (object)[
                        'patient_name' => $parent->pr_BabyName,
                        'patient_id'   => $parent->formattedID ?? '#P'.$parent->pr_ID,
                        'patient_nicu' => $parent->pr_NICU,
                        'parent_consent' => $parent->pr_ConsentStatus ?? 'N/A',
                        
                        'donor_id'     => $donorIDStr,
                        'donor_name'   => $donorName,
                        'consent'      => 'Consent Granted',
                        
                        'method'       => $req->kinship_method === 'yes' ? 'Milk Kinship' : 'No Milk Kinship',
                        'schedule'     => $req->feeding_perday . ' feeds/day (' . $req->feeding_interval . 'h interval)',
                        'start_time'   => $req->feeding_start_time,
                        'doctor_id'    => $req->doctor ? '#Dr'.$req->doctor->dr_ID : '-',
                        'doctor_name'  => $req->doctor ? $req->doctor->dr_Name : 'Unknown',
                        'status'       => $req->status,
                        'direct_oral'  => $req->oral_feeding,
                        'feeding_tube' => $req->feeding_tube,
                        'oral_volume'  => $req->oral_total . ' ml',
                        'tube_volume'  => $req->drip_total . ' ml',
                        'allocations'  => $allocationList
                    ]
                ];
            }
            
            if (!empty($infantObj->requests)) {
                $infants[] = $infantObj;
            }
        }

        return view('donor.donor_infants-request', [
            'infants' => $infants, 
            'requests' => $paginatedParentIDs
        ]);
    }

    public function viewTraceabilityHMMC(Request $request)
    {

        $requestQuery = MilkRequest::whereIn('status', ['Allocated', 'Fully Dispensed']);

        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $requestQuery->whereHas('parent', function($q) use ($searchTerm) {
                $q->where('pr_BabyName', 'like', "%{$searchTerm}%")
                ->orWhere('pr_ID', 'like', "%{$searchTerm}%");
            });
        }

        $parentIDs = $requestQuery->pluck('pr_ID')->unique();
        
        // Manual Pagination for the array of IDs
        $page = $request->input('page', 1);
        $perPage = 10;
        $paginatedParentIDs = new \Illuminate\Pagination\LengthAwarePaginator(
            $parentIDs->forPage($page, $perPage),
            $parentIDs->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $infants = [];
        
        $visibleRequests = MilkRequest::whereIn('status', ['Allocated', 'Fully Dispensed'])
            ->whereIn('pr_ID', $paginatedParentIDs->items()) // Filter by the paginated IDs
            ->with([
                'parent', 
                'doctor',
                'allocations.postBottles.milk.donor', 
                'allocations.nurse'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('parent.pr_ID');

        foreach ($visibleRequests as $patientID => $patientRequests) {
            $firstReq = $patientRequests->first(); 
            $parent = $firstReq->parent;

            // Build the infant object structure
            $infantObj = (object)[
                'id' => $parent->formattedID ?? 'ID-'.$parent->pr_ID,
                'raw_id' => $parent->pr_ID,
                'name' => $parent->pr_BabyName,
                'nicu' => $parent->pr_NICU,
                'last_updated' => $patientRequests->max('updated_at')->format('d/m/Y • h:i A'),
                'current_weight' => $firstReq->current_weight,
                'baby_gender' => $parent->pr_BabyGender,
                'status' => $firstReq->status,
                'requests' => []
            ];

            // Process each request for this infant
            foreach ($patientRequests as $req) {
                
                // Map Allocations
                $allocationList = $req->allocations->map(function($alloc) {
                    return (object)[
                        'milk_id'    => $alloc->postBottles ? $alloc->postBottles->post_bottle_code : 'N/A',
                        'volume'     => $alloc->total_selected_milk,
                        'time'       => $alloc->created_at->format('Y-m-d h:i A'),
                        'nurse_id'   => $alloc->nurse ? '#N'.$alloc->nurse->ns_ID : '-',
                        'nurse_name' => $alloc->nurse ? $alloc->nurse->ns_Name : 'Unknown'
                    ];
                });

                // Get Donor Info
                $firstAlloc = $req->allocations->first();
                $donor = $firstAlloc && $firstAlloc->postBottles && $firstAlloc->postBottles->milk && $firstAlloc->postBottles->milk->donor 
                    ? $firstAlloc->postBottles->milk->donor 
                    : null;

                // Build the Request Detail Object
                $infantObj->requests[] = (object)[
                    'req_id' => $req->request_ID,
                    'total_allocated_vol' => $req->allocations->sum('total_selected_milk'),
                    'details' => (object)[
                        'patient_name' => $parent->pr_BabyName,
                        'patient_id'   => $parent->formattedID ?? '#P'.$parent->pr_ID,
                        'patient_nicu' => $parent->pr_NICU,
                        'parent_consent' => $parent->pr_ConsentStatus ?? 'N/A',
                        'donor_id'     => $donor ? '#D'.$donor->dn_ID : 'Mixed/Unknown',
                        'donor_name'   => $donor ? $donor->dn_FullName : 'Multiple/Unknown',
                        'consent'      => 'Consent Granted',
                        'method'       => $req->kinship_method === 'yes' ? 'Milk Kinship' : 'No Milk Kinship',
                        'schedule'     => $req->feeding_perday . ' feeds/day (' . $req->feeding_interval . 'h interval)',
                        'start_time'   => $req->feeding_start_time,
                        'doctor_id'    => $req->doctor ? '#Dr'.$req->doctor->dr_ID : '-',
                        'doctor_name'  => $req->doctor ? $req->doctor->dr_Name : 'Unknown',
                        'status'       => $req->status,
                        'direct_oral'  => $req->oral_feeding,
                        'feeding_tube' => $req->feeding_tube,
                        'oral_volume'  => $req->oral_total . ' ml',
                        'tube_volume'  => $req->drip_total . ' ml',
                        'allocations'  => $allocationList
                    ]
                ];
            }
            $infants[] = $infantObj;
        }

        return view('hmmc.hmmc_infants-request', ['infants' => $infants, 'requests' => $paginatedParentIDs]);
    }

    public function viewTraceabilityShariah(Request $request)
    {

        $requestQuery = MilkRequest::whereIn('status', ['Allocated', 'Fully Dispensed']);

        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $requestQuery->whereHas('parent', function($q) use ($searchTerm) {
                $q->where('pr_BabyName', 'like', "%{$searchTerm}%")
                ->orWhere('pr_ID', 'like', "%{$searchTerm}%");
            });
        }

        $parentIDs = $requestQuery->pluck('pr_ID')->unique();
        
        // Manual Pagination for the array of IDs
        $page = $request->input('page', 1);
        $perPage = 10;
        $paginatedParentIDs = new \Illuminate\Pagination\LengthAwarePaginator(
            $parentIDs->forPage($page, $perPage),
            $parentIDs->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $infants = [];
        
        $visibleRequests = MilkRequest::whereIn('status', ['Allocated', 'Fully Dispensed'])
            ->whereIn('pr_ID', $paginatedParentIDs->items()) // Filter by the paginated IDs
            ->with([
                'parent', 
                'doctor',
                'allocations.postBottles.milk.donor', 
                'allocations.nurse'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('parent.pr_ID');

        foreach ($visibleRequests as $patientID => $patientRequests) {
            $firstReq = $patientRequests->first(); 
            $parent = $firstReq->parent;

            // Build the infant object structure
            $infantObj = (object)[
                'id' => $parent->formattedID ?? 'ID-'.$parent->pr_ID,
                'raw_id' => $parent->pr_ID,
                'name' => $parent->pr_BabyName,
                'nicu' => $parent->pr_NICU,
                'last_updated' => $patientRequests->max('updated_at')->format('d/m/Y • h:i A'),
                'current_weight' => $firstReq->current_weight,
                'baby_gender' => $parent->pr_BabyGender,
                'status' => $firstReq->status,
                'requests' => []
            ];

            // Process each request for this infant
            foreach ($patientRequests as $req) {
                
                // Map Allocations
                $allocationList = $req->allocations->map(function($alloc) {
                    return (object)[
                        'milk_id'    => $alloc->postBottles ? $alloc->postBottles->post_bottle_code : 'N/A',
                        'volume'     => $alloc->total_selected_milk,
                        'time'       => $alloc->created_at->format('Y-m-d h:i A'),
                        'nurse_id'   => $alloc->nurse ? '#N'.$alloc->nurse->ns_ID : '-',
                        'nurse_name' => $alloc->nurse ? $alloc->nurse->ns_Name : 'Unknown'
                    ];
                });

                // Get Donor Info
                $firstAlloc = $req->allocations->first();
                $donor = $firstAlloc && $firstAlloc->postBottles && $firstAlloc->postBottles->milk && $firstAlloc->postBottles->milk->donor 
                    ? $firstAlloc->postBottles->milk->donor 
                    : null;

                // Build the Request Detail Object
                $infantObj->requests[] = (object)[
                    'req_id' => $req->request_ID,
                    'total_allocated_vol' => $req->allocations->sum('total_selected_milk'),
                    'details' => (object)[
                        'patient_name' => $parent->pr_BabyName,
                        'patient_id'   => $parent->formattedID ?? '#P'.$parent->pr_ID,
                        'patient_nicu' => $parent->pr_NICU,
                        'parent_consent' => $parent->pr_ConsentStatus ?? 'N/A',
                        'donor_id'     => $donor ? '#D'.$donor->dn_ID : 'Mixed/Unknown',
                        'donor_name'   => $donor ? $donor->dn_FullName : 'Multiple/Unknown',
                        'consent'      => 'Consent Granted',
                        'method'       => $req->kinship_method === 'yes' ? 'Milk Kinship' : 'No Milk Kinship',
                        'schedule'     => $req->feeding_perday . ' feeds/day (' . $req->feeding_interval . 'h interval)',
                        'start_time'   => $req->feeding_start_time,
                        'doctor_id'    => $req->doctor ? '#Dr'.$req->doctor->dr_ID : '-',
                        'doctor_name'  => $req->doctor ? $req->doctor->dr_Name : 'Unknown',
                        'status'       => $req->status,
                        'direct_oral'  => $req->oral_feeding,
                        'feeding_tube' => $req->feeding_tube,
                        'oral_volume'  => $req->oral_total . ' ml',
                        'tube_volume'  => $req->drip_total . ' ml',
                        'allocations'  => $allocationList
                    ]
                ];
            }
            $infants[] = $infantObj;
        }

        return view('shariah.shariah_infants-request', ['infants' => $infants, 'requests' => $paginatedParentIDs]);
    }

    
    /**
     * Store the allocation of milk bottles to a request.
     * Captures Nurse ID (ns_ID) here.
     */
    public function allocateMilk(Request $request)
    {
        // 1. Validate Input
        $request->validate([
            'request_id'       => 'required|exists:request,request_ID',
            'selected_milk'    => 'required|array|min:1', 
            'selected_milk.*.id' => 'required',           
            'selected_milk.*.volume' => 'required|numeric',
            'storage_location' => 'required|string'
        ]);

        try {
            DB::beginTransaction();
            // Find Nurse by Auth User ID
            $nurse = \App\Models\Nurse::where('user_id', Auth::id())->first();


            // 2. Find Request
            $milkRequest = MilkRequest::findOrFail($request->request_id);

            if ($milkRequest->status === 'Allocated') {
                return response()->json(['success' => false, 'message' => 'Request already allocated.'], 400);
            }

            if (!$nurse) {
                // Check if the user acts as the nurse directly (for simple setups)
                // OR throw an error if no nurse profile is found.
                // For now, let's assume if lookup fails, we try Auth::id() ONLY if it exists in nurse table.
                
                // BETTER FIX: Stop and tell the user the account is invalid
                return response()->json([
                    'success' => false, 
                    'message' => 'Error: Current user is not registered as a Nurse (No Nurse ID found).'
                ], 403);
            }

            // 3. Get Current Nurse ID
            // Adjust this based on your Auth setup. 
            // If the user IS the nurse: Auth::id() or Auth::user()->ns_ID
            $nurseID = $nurse->ns_ID;

            // 4. Create Allocations
            foreach ($request->selected_milk as $item) {
                Allocation::create([
                    'request_ID'          => $milkRequest->request_ID,
                    'post_ID'             => $item['id'],
                    'ns_ID'               => $nurseID, // <--- SAVED HERE NOW
                    'total_selected_milk' => $item['volume'],
                    'storage_location'    => $request->storage_location,
                    'allocation_milk_date_time' => [
                        'post_ID'  => $item['id'],
                        'datetime' => now()->toDateTimeString()
                    ]
                ]);
            }

            // 5. Update Status to 'Allocated'
            $milkRequest->status = 'Allocated';
            $milkRequest->save();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Milk allocated successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Error allocating milk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle the dispensing of milk bottles.
     * Updates only dispense date and time.
     */
    public function dispenseMilk(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.allocation_id' => 'required|exists:allocation,allocation_ID',
            'items.*.method' => 'required|in:oral,tube', // Validate method
        ]);

        try {
            DB::beginTransaction();
            
            foreach ($request->items as $item) {
                $allocation = Allocation::findOrFail($item['allocation_id']);
                
                $allocation->dispense_date = Carbon::now()->toDateString();
                $allocation->dispense_time = Carbon::now()->toTimeString();
                
                // You can save the method here if you have a column for it
                // $allocation->fed_method = $item['method']; 

                $allocation->save();
            }

            // ... (Rest of logic: update parent status, commit) ...

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteAllocation(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:request,request_ID',
        ]);

        // Delete allocations
        Allocation::where('request_ID', $request->request_id)->delete();

        // Revert status
        $milkRequest = MilkRequest::findOrFail($request->request_id);
        $milkRequest->status = 'Waiting'; // Or 'Approved' depending on your flow
        $milkRequest->save();

        return response()->json([
            'success' => true, 
            'message' => 'Allocation reverted successfully.'
        ]);
    }

    /**
     * STEP 1: Save the Feeding Plan
     * Triggered when "CONFIRM ALLOCATION" is clicked in the modal selection grid.
     */
    public function saveFeedingPlan(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:request,request_ID',
            'items' => 'required|array',
            'items.*.allocation_id' => 'required|exists:allocation,allocation_ID',
            'items.*.method' => 'required|in:tube,oral' // Validate the specific values
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->items as $item) {
                // Update the Allocation table
                Allocation::where('allocation_ID', $item['allocation_id'])->update([
                    'feeding_method' => $item['method'], // Save 'tube' or 'oral'
                    
                    // Only set dispense timestamp if not already set (optional logic)
                    // 'dispense_date' => now()->toDateString(),
                    // 'dispense_time' => now()->toTimeString(),
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Feeding plan saved.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * STEP 2: Log a single feed tick
     * Triggered when a 7.5ml checkbox is ticked or Tube is verified.
     */
    public function logFeedRecord(Request $request)
    {
        // Use the exact primary key column name 'allocation_ID' in validation
        $request->validate([
            'allocation_id' => 'required|exists:allocation,allocation_ID',
            'fed_volume'    => 'required|numeric|min:0.1'
        ]);

        try {
            // 1. Find the nurse linked to the authenticated user
            $nurse = Nurse::where('user_id', Auth::id())->first();
            if (!$nurse) {
                return response()->json(['success' => false, 'message' => 'Nurse profile not found.'], 403);
            }

            // 2. Create the record
            // Force the timestamp to a string format to avoid DB driver issues with Carbon objects
            $record = FeedRecord::create([
                'allocation_ID' => $request->allocation_id,
                'ns_ID'         => $nurse->ns_ID,
                'fed_volume'    => $request->fed_volume,
                'fed_at'        => now()->toDateTimeString(),
            ]);

            // 3. Find allocation and check consumption
            // Ensure we load records to calculate the sum correctly
            $allocation = Allocation::with('feedRecords')->find($request->allocation_id);

            if (!$allocation) {
                return response()->json(['success' => false, 'message' => 'Allocation record not found.'], 404);
            }

            $totalFed = $allocation->feedRecords->sum('fed_volume');

            // 4. Update consumption status if bottle is empty
            if ($totalFed >= $allocation->total_selected_milk) {
                $allocation->update(['is_consumed' => true]);
            }

            return response()->json([
                'success'    => true,
                'nurse_name' => $nurse->ns_Name,
                'time'       => $record->fed_at->format('h:i A'),
                'date'       => $record->fed_at->format('d M Y')
            ]);

        } catch (\Exception $e) {
            // Return the actual error message in the response for easier debugging
            return response()->json([
                'success' => false, 
                'message' => 'Backend Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * STEP 3: Finalize ("Confirm & Lock")
     */
    public function finalizeDispensing(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:request,request_ID'
        ]);

        $milkRequest = MilkRequest::findOrFail($request->request_id);
        $milkRequest->status = 'Fully Dispensed';
        $milkRequest->save();

        return response()->json(['success' => true, 'message' => 'Feeding results locked.']);
    }


    public function generateMilkReport(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:parent,pr_ID' // Ensure patient exists
        ]);

        $patientId = $request->patient_id;

        // 1. Fetch Patient Info
        $patient = ParentModel::findOrFail($patientId); // Adjust Model name if it's 'Patient' or 'ParentModel'

        // 2. Fetch All Allocated Milk Requests for this Patient
        // We need to drill down: Request -> Allocations -> PostBottle -> Milk -> Donor
        $requests = MilkRequest::where('pr_ID', $patientId)
            ->whereIn('status', ['Allocated', 'Fully Dispensed'])
            ->with([
                'doctor',
                'allocations.nurse',
                'allocations.feedRecords.nurse', // To get witness/checker if available
                'allocations.postBottles.milk.donor'
            ])
            ->orderBy('created_at', 'asc') // Chronological order
            ->get();

        // 3. Flatten allocations into a single list for the table
        $reportData = [];
        $totalVolume = 0;

        foreach ($requests as $req) {
            foreach ($req->allocations as $alloc) {
                
                // Get Donor Info safely
                $bottle = $alloc->postBottles;
                $donor = $bottle && $bottle->milk ? $bottle->milk->donor : null;

                // Determine Signature Names
                $inchargeName = $alloc->nurse ? $alloc->nurse->ns_Name : 'N/A';
                
                $witnessName = $req->doctor ? $req->doctor->dr_Name : '-';

                // Format Date/Time
                $dateTime = $alloc->created_at; // Or dispense_time if available

                $reportData[] = (object)[
                    'date' => $dateTime->format('d/m/y'),
                    'time' => $dateTime->format('H:i') . ' H',
                    'batch_no' => $bottle ? $bottle->post_bottle_code : 'N/A', // Milk ID
                    'donor_id' => $donor ? '#D'.$donor->dn_ID : 'Unknown',
                    'consent_kinship' => $req->kinship_method === 'yes' ? 'YES' : 'NO',
                    'freq' => $req->feeding_interval . ' H',
                    'amount' => $alloc->total_selected_milk,
                    'incharge' => $inchargeName,
                    'witness' => $witnessName,
                    'remark' => $req->feeding_tube ? 'Feeding Tube' : 'Direct Oral',
                    'gestational_age' => $req->gestational_age ?? 'N/A'
                ];

                $totalVolume += $alloc->total_selected_milk;
            }
        }

        $latestRequest = $requests->last(); 
        $gestationalAge = $latestRequest ? $latestRequest->gestational_age : '-';

        // 4. Pass data to the PDF View
        return view('layouts.milk_report_pdf', [
            'patient' => $patient,
            'gestationalAge' => $gestationalAge,
            'reportData' => $reportData,
            'totalVolume' => $totalVolume,
            'generatedDate' => now()->format('Y-m-d')
        ]);
    }
}