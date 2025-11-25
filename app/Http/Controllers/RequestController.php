<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentModel;
use App\Models\Request as MilkRequest;
use Illuminate\Support\Facades\Auth;
use App\Model\Doctor;

class RequestController extends Controller
{
    public function create()
    {
        $parents = ParentModel::all();

        return view('doctor.doctor_milk-request-form', compact('parents'));
    }

    public function view()
    {
        $requests = MilkRequest::with(['parent', 'doctor'])->latest()->get();

        return view('doctor.doctor_milk-request', compact('requests'));
    }

    public function store(Request $request)
    {
        $doctor = \App\Models\Doctor::where('dr_ID', auth()->id())->first();

        $request->validate([
            'pr_ID'             => 'required|exists:parent,pr_ID',
            'weight'            => 'required|numeric|min:0.1',
            'entered_volume'    => 'required|numeric|min:1',
            'feeding_date'      => 'required|date',
            'start_time'        => 'required',
            'feeds_per_day'     => 'required|integer|min:1',
            'interval_hours'    => 'required|integer|min:1',
        ]);

        MilkRequest::create([
            'dr_ID'              => $doctor->dr_ID, // doctor logged in
            'pr_ID'              => $request->pr_ID,
            'current_weight'     => $request->weight,
            'recommended_volume' => $request->entered_volume,
            'feeding_start_date' => $request->feeding_date,
            'feeding_start_time' => $request->start_time,
            'feeding_perday'     => $request->feeds_per_day,
            'feeding_interval'   => $request->interval_hours,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Milk Request submitted successfully!'
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

}