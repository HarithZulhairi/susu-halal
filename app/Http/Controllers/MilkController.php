<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donor;
use App\Models\Milk;

class MilkController extends Controller
{
    // MilkController.php
    public function showDonorinForm()
    {
        $donors = Donor::all();

        // Get all milk records with donor info
        $milks = Milk::with('donor')
            ->orderByDesc('created_at')
            ->get();

        return view('labtech.labtech_manage-milk-records', compact('donors', 'milks'));
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



}
