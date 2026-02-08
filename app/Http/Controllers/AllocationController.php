<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Allocation;
use App\Models\Request as MilkRequest; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Import Auth
use Carbon\Carbon;

class AllocationController extends Controller
{
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
        // 1. Validate Items & Volume Inputs
        $request->validate([
            'items' => 'required|array|min:1', // At least one bottle checked
            'items.*.allocation_id' => 'required|exists:allocation,allocation_ID',
            
            // Validate the volumes (nullable allows for the Kinship logic)
            'oral_volume' => 'nullable|numeric|min:0',
            'tube_volume' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            $requestIDs = []; 
            $nurseID = Auth::user()->ns_ID ?? Auth::id(); // Capture dispensing nurse if needed logic changes

            // 2. Loop through CHECKED items (Bottles used)
            foreach ($request->items as $item) {
                $allocation = Allocation::findOrFail($item['allocation_id']);
                
                // Update Dispense Info
                $allocation->dispense_date = Carbon::now()->toDateString();
                $allocation->dispense_time = Carbon::now()->toTimeString();
                
                // NOTE: If you add columns to your DB, save the volumes here:
                // $allocation->fed_oral_vol = $request->oral_volume;
                // $allocation->fed_tube_vol = $request->tube_volume;

                $allocation->save();
                $requestIDs[] = $allocation->request_ID;
            }

            // 3. Update Parent Request Status
            $uniqueRequestIDs = array_unique($requestIDs);
            foreach ($uniqueRequestIDs as $reqID) {
                $req = MilkRequest::find($reqID);
                if ($req) {
                    $undispensedCount = Allocation::where('request_ID', $reqID)
                                                  ->whereNull('dispense_date')
                                                  ->count();
                    
                    if ($undispensedCount === 0) {
                        $req->status = 'Fully Dispensed';
                    } else {
                        $req->status = 'Allocated'; 
                    }
                    $req->save();
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Milk bottles marked as dispensed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
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
}