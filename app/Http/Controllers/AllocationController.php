<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Allocation;
use App\Models\FeedRecord;
use App\Models\Nurse;
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
            'items.*.method' => 'required|in:tube,oral'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->items as $item) {
                Allocation::where('allocation_ID', $item['allocation_id'])->update([
                    'feeding_method' => $item['method'],
                    'dispense_date' => now()->toDateString(),
                    'dispense_time' => now()->toTimeString(),
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
        $request->validate([
            'allocation_id' => 'required|exists:allocation,allocation_ID',
            'fed_volume' => 'required|numeric|min:0.1'
        ]);

        try {
            // Find current Nurse ID linked to User
            $nurse = Nurse::where('user_id', Auth::id())->first();
            if (!$nurse) {
                return response()->json(['success' => false, 'message' => 'Nurse profile not found.'], 403);
            }

            $record = FeedRecord::create([
                'allocation_ID' => $request->allocation_id,
                'ns_ID' => $nurse->ns_ID,
                'fed_volume' => $request->fed_volume,
                'fed_at' => now(),
            ]);

            // Check if bottle is now fully consumed
            $allocation = Allocation::with('feedRecords')->find($request->allocation_id);
            $totalFed = $allocation->feedRecords->sum('fed_volume');

            if ($totalFed >= $allocation->total_selected_milk) {
                $allocation->update(['is_consumed' => true]);
            }

            return response()->json([
                'success' => true,
                'nurse_name' => $nurse->ns_Name,
                'time' => $record->fed_at->format('h:i A')
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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
}