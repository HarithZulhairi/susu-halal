<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Allocation;
use App\Models\Request as MilkRequest;
use Illuminate\Support\Facades\DB;

class AllocationController extends Controller
{
    public function allocateMilk(Request $request)
    {
        // 1. Validate Input
        $request->validate([
            'request_id'       => 'required|exists:request,request_ID',
            'selected_milk'    => 'required|array|min:1', // Must select at least one
            'selected_milk.*.id' => 'required',           // Validate structure of array
            'selected_milk.*.volume' => 'required|numeric',
            'storage_location' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            // 2. Find Request
            $milkRequest = MilkRequest::findOrFail($request->request_id);

            // Optional: Check if already allocated to prevent double submission
            if ($milkRequest->status === 'Allocated') {
                return response()->json(['success' => false, 'message' => 'Request already allocated.'], 400);
            }

            // 3. Create Allocations
            // We loop through the selected milk array sent from frontend
            foreach ($request->selected_milk as $item) {
                Allocation::create([
                    'request_ID'          => $milkRequest->request_ID,
                    'post_ID'             => $item['id'],
                    'total_selected_milk' => $item['volume'], // Volume of THIS bottle
                    'storage_location'    => $request->storage_location,
                    
                    // You requested saving time, assuming current time if not provided
                    'allocation_milk_date_time' => [
                        'post_ID' => $item['id'],
                        'datetime' => now()->toDateTimeString()
                    ]
                ]);
            }

            // 4. Update Status to 'Allocated'
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
}
