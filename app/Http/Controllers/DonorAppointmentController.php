<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MilkAppointment;
use App\Models\PumpingKitAppointment;
use App\Models\DonorToBe;

class DonorAppointmentController extends Controller
{
    // Store Milk Donation Appointment
    public function storeMilkAppointment(Request $request)
    {
        // Ensure donor is logged in (grab role_id directly)
        $dn_id = auth()->user()->role_id;  // Since donors have role_id mapped to dn_ID

        if (!$dn_id) {
            return redirect()->back()->with('error', 'Donor not found. Please relogin.');
        }

        // Validate the request (remove dn_ID from validation)
        $request->validate([
            'milk_amount' => 'required|numeric|min:1|max:1000',
            'delivery_method' => 'required',
            'appointment_datetime' => 'required|date',
        ]);

        // Normalize fields based on delivery type
        $location = $request->delivery_method === 'delivery' ? $request->location : null;
        $collection_address = $request->delivery_method === 'pick_up' ? $request->collection_address : null;

        
        // STEP 1: Create record
        $appointment = MilkAppointment::create([
            'dn_ID' => $dn_id,
            'milk_amount' => $request->milk_amount,
            'delivery_method' => $request->delivery_method,
            'location' => $location,
            'collection_address' => $collection_address,
            'appointment_datetime' => $request->appointment_datetime,
            'remarks' => $request->remarks,
            'status' => 'Pending',
        ]);

        /**
         * STEP 2: Find last appointment for this donor
         */
        $lastEntry = MilkAppointment::where('dn_ID', $dn_id)
            ->whereNotNull('reference_num')
            ->orderByDesc('reference_num')
            ->first();

        // Determine next sequence (last 3 digits)
        $nextNumber = $lastEntry
            ? ((int) substr($lastEntry->reference_num, -3) + 1)
            : 1;

        /**
         * STEP 3: Generate new reference number
         * FORMAT: MDN-2025-[DONOR][RUNNING]
         * Example: MDN-2025-012001
         */
        $refCode = "MDN-2025-" 
            . str_pad($dn_id, 3, '0', STR_PAD_LEFT)
            . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        

        // Save formatted version
        $appointment->update(['reference_num' => $refCode]);

        // Clear form session
        session()->forget('milkDonationAppointment');

        return redirect()
            ->route('donor.appointments')
            ->with('success_created', "Appointment created!")
            ->with('reference', $refCode);;
            
    }

    public function storePumpingKitAppointment(Request $request)
    {
        

        // Ensure donor is logged in (grab role_id directly)
        $dn_id = auth()->user()->role_id;  // Since donors have role_id mapped to dn_ID

        if (!$dn_id) {
            return redirect()->back()->with('error', 'Donor not found. Please relogin.');
        }

        

        // Validate the request (remove dn_ID from validation)
        $request->validate([
            'kit_type' => 'required',
            'location' => 'required',
            'appointment_datetime' => 'required|date',
        ]);
    
        
        // STEP 1: Create record
        $appointment = PumpingKitAppointment::create([
            'dn_ID' => $dn_id,
            'kit_type' => $request->kit_type,
            'location' => $request->location,
            'appointment_datetime' => $request->appointment_datetime,
            'reason' => $request->reason,
            'status' => 'Pending',
        ]);

        /**
         * STEP 2: Find last appointment for this donor
         */
        $lastEntry = PumpingKitAppointment::where('dn_ID', $dn_id)
            ->whereNotNull('reference_num')
            ->orderByDesc('reference_num')
            ->first();

        // Determine next sequence (last 3 digits)
        $nextNumber = $lastEntry
            ? ((int) substr($lastEntry->reference_num, -3) + 1)
            : 1;

        /**
         * STEP 3: Generate new reference number
         * FORMAT: MDN-2025-[DONOR][RUNNING]
         * Example: MDN-2025-012001
         */
        $refCode = "PKN-2025-" 
            . str_pad($dn_id, 3, '0', STR_PAD_LEFT)
            . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        

        // Save formatted version
        $appointment->update(['reference_num' => $refCode]);

        // Clear form session
        session()->forget('pumpingKitAppointment');

        return redirect()
            ->route('donor.appointments')
            ->with('success_pk', "Appointment created!")
            ->with('reference', $refCode);;
            
    }

    public function showAppointment()
    {
        $dn_id = auth()->user()->role_id;

        // ---- MILK APPOINTMENTS ----
        $milk = MilkAppointment::where('dn_id', $dn_id)
            ->select(
                'reference_num',
                'ma_ID',
                'appointment_datetime',
                'milk_amount',
                'delivery_method as type',
                'location',
                'collection_address',
                'status',
                'remarks'
            )
            ->get()
            ->map(function ($item) {
                $item->appointment_category = "Milk Donation";
                return $item;
            });

        // ---- PUMPING KIT APPOINTMENTS ----
        $pumping = PumpingKitAppointment::where('dn_id', $dn_id)
            ->select(
                'reference_num',
                'pk_ID',
                'appointment_datetime',
                'kit_type as type',
                'location',
                'status',
                'reason as remarks'
            )
            ->get()
            ->map(function ($item) {
                $item->milk_amount = null;
                $item->appointment_category = "Pumping Kit";
                return $item;
            });

         $allAppointments = $milk->concat($pumping)
        ->sortByDesc('appointment_datetime')
        ->values();

        // ---- SPLIT LISTS ----
        $currentAppointments = $allAppointments->filter(function ($item) {
            return in_array($item->status, ['Pending', 'Confirmed']);
        });

        $historyAppointments = $allAppointments->filter(function ($item) {
            return in_array($item->status, ['Canceled', 'Completed']);
        });

        // ---- COUNTERS ----
        $total = $milk->count() + $pumping->count();
        $pending = $milk->where('status', 'Pending')->count() + $pumping->where('status', 'Pending')->count(); 
        $confirmed = $milk->where('status', 'Confirmed')->count() + $pumping->where('status', 'Confirmed')->count(); 
        $completed = $milk->where('status', 'Completed')->count() + $pumping->where('status', 'Completed')->count(); 
        $canceled = $milk->where('status', 'Canceled')->count() + $pumping->where('status', 'Canceled')->count();
        
        return view('donor.donor_appointments', compact(
            'currentAppointments',
            'historyAppointments',
            'total',
            'pending',
            'confirmed',
            'completed',
            'canceled'
        ));
    }

    public function updateMilkAppointment(Request $request, $id)
    {
        $request->validate([
            'edit_datetime' => 'required|date',
            'remarks' => 'nullable|string'
        ]);

        $reAppointment = MilkAppointment::findOrFail($id);

        $reAppointment->update([
            'appointment_datetime' => $request->edit_datetime,
            'remarks' => $request->remarks,
            'status' => 'Pending'
        ]);

        return redirect()
            ->route('donor.appointments')
            ->with('success_updated', 'Milk Appointment updated successfully.')
            ->with('reference', $reAppointment->reference_num);
    }

    public function updatePumpingKitAppointment(Request $request, $id)
    {
        $request->validate([
            'edit_datetime' => 'required|date',
            'remarks' => 'nullable|string'
        ]);

        $reAppointment = PumpingKitAppointment::findOrFail($id);

        $reAppointment->update([
            'appointment_datetime' => $request->edit_datetime,
            'reason' => $request->remarks, // PumpingKit uses 'reason' field instead of remarks
            'status' => 'Pending'
        ]);

        return redirect()
            ->route('donor.appointments')
            ->with('success_updated', 'Pumping Kit Appointment updated successfully.')
            ->with('reference', $reAppointment->reference_num);
    }


    public function cancelMilk(Request $request, $id)
    {
        
        $appointment = MilkAppointment::findOrFail($id);

        $now = now();
        $appointmentTime = \Carbon\Carbon::parse($appointment->appointment_datetime);

        $hoursDifference = $now->diffInHours($appointmentTime, false);
        
        // Appointment is in the future AND less than 24 hours away
        if ($hoursDifference > 0 && $hoursDifference < 24) {
            return redirect()
            ->route('donor.appointments')
            ->with('too_close', true)
            ->with('cantCancelRef', $appointment->reference_num);
        }

        
        $appointment->update(['status' => 'Canceled']);

        

        return redirect()
            ->route('donor.appointments')
            ->with('success_maCancel', 'Milk Appointment canceled successfully.')
            ->with('reference', $appointment->reference_num);
    }


    public function cancelPumpingKit(Request $request, $id)
    {
        
        $appointment = PumpingKitAppointment::findOrFail($id);

        $now = now();
        $appointmentTime = \Carbon\Carbon::parse($appointment->appointment_datetime);

        $hoursDifference = $now->diffInHours($appointmentTime, false);

        // Appointment is in the future AND less than 24 hours away
        if ($hoursDifference > 0 && $hoursDifference < 24) {
            return redirect()
            ->route('donor.appointments')
            ->with('too_close', true)
            ->with('cantCancelRef', $appointment->reference_num);
        }

        $appointment->update(['status' => 'Canceled']);

        return redirect()
            ->route('donor.appointments')
            ->with('success_pkCancel', 'Pumping Kit Appointment canceled successfully.')
            ->with('reference', $appointment->reference_num);
    }

    //NURSE

    public function nurseViewAppointments()
    {
        // Get all Milk Appointments
        $milkAppointments = MilkAppointment::orderBy('appointment_datetime', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'reference' => $item->reference_num,
                    'donor_id'  => $item->dn_id,
                    'date'      => $item->appointment_datetime,
                    'amount'    => $item->milk_amount,
                    'status'    => $item->status,
                    'type'      => strtoupper(str_replace('_', ' ', $item->delivery_method)),
                    'location'  => $item->location ?? $item->collection_address,
                ];
            });

        // Get all Pumping Kit Appointments
        $pumpingAppointments = PumpingKitAppointment::orderBy('appointment_datetime', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'reference' => $item->reference_num,
                    'donor_id'  => $item->dn_ID,
                    'date'      => $item->appointment_datetime,
                    'status'    => $item->status,
                    'location'  => $item->location,
                ];
            });

        return view('nurse.nurse_donor-appointment-record', [
            'milkAppointments'    => $milkAppointments,
            'pumpingAppointments' => $pumpingAppointments,
        ]);
    }

    public function nurseConfirmMilkAppointment($reference)
    {
        $app = MilkAppointment::where('reference_num', $reference)->firstOrFail();
        $app->status = "Confirmed";
        $app->save();

        return back()->with('success', "Milk appointment $reference completed.");
    }

    public function nurseConfirmPumpingKitAppointment($reference)
    {
        $app = PumpingKitAppointment::where('reference_num', $reference)->firstOrFail();
        $app->status = "Confirmed";
        $app->save();

        return back()->with('success', "Pumping kit appointment $reference completed.");
    }

    public function showAllDonorToBeNurse()
    {
        // Load all candidates from DB
        $dtb = DonorToBe::orderBy('created_at', 'desc')
            ->get();

        // ---- COUNTERS ----
        $total = $dtb->count();
        $pending = $dtb->where('dtb_ScreeningStatus', 'pending')->count(); 
        $passed = $dtb->where('dtb_ScreeningStatus', 'passed')->count(); 
        $rejected = $dtb->where('dtb_ScreeningStatus', 'rejected')->count(); 

        return view('nurse.nurse_donor-candidate-list', [
            'dtb' => $dtb,
            'total'      => $total,
            'pending'    => $pending,
            'passed'     => $passed,
            'rejected'   => $rejected,
        ]);
    }



    /**
     * Approve donor candidate screening
     */
    public function approveDonorToBeNurse(Request $request, $id)
    {
        $dtb = DonorToBe::findOrFail($id);

        // Update statuses
        $dtb->dtb_ScreeningStatus = 'passed';

        // Optional remarks
        if ($request->filled('dtb_ScreeningStatus')) {
            $dtb->dtb_ScreeningStatus = $request->dtb_ScreeningStatus;
        }

        $nurse_id = auth()->user()->role_id;

        //Update Nurse ID
        $dtb->ns_ID = $nurse_id;
        $dtb->dtb_ScreenedAt = now();

        $dtb->save();

        return redirect()
            ->back()
            ->with('success', 'Candidate screening approved successfully.');
    }



    /**
     * Reject donor candidate screening
     */
    public function rejectDonorToBeNurse(Request $request, $id)
    {
        // Validate reason
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $dtb = DonorToBe::findOrFail($id);

        // Update statuses
        $dtb->dtb_ScreeningStatus = 'failed';

        // Save rejection reason
        $dtb->dtb_ScreeningRemark = $request->reason;
        
        $nurse_id = auth()->user()->role_id;
        //Update Nurse ID
        $dtb->ns_ID = $nurse_id;
        $dtb->dtb_ScreenedAt = now();

        $dtb->save();

        return redirect()
            ->back()
            ->with('success', 'Candidate screening rejected.');
    }

    public function showAllDonorToBeDr()
    {
        // Load all candidates from DB
        $dtb = DonorToBe::orderBy('created_at', 'desc')
            ->get();

        // ---- COUNTERS ----
        $total = $dtb->count();
        $pending = $dtb->where('dtb_ScreeningStatus', 'pending')->count(); 
        $passed = $dtb->where('dtb_ScreeningStatus', 'passed')->count(); 
        $rejected = $dtb->where('dtb_ScreeningStatus', 'rejected')->count(); 

        return view('doctor.doctor_donor-candidates', [
            'dtb' => $dtb,
            'total'      => $total,
            'pending'    => $pending,
            'passed'     => $passed,
            'rejected'   => $rejected,
        ]);
    }



    /**
     * Approve donor candidate screening
     */
    public function approveDonorToBeDr(Request $request, $id)
    {
        $dtb = DonorToBe::findOrFail($id);

        // Update statuses
        $dtb->dtb_ScreeningStatus = 'passed';

        // Optional remarks
        if ($request->filled('dtb_ScreeningStatus')) {
            $dtb->dtb_ScreeningStatus = $request->dtb_ScreeningStatus;
        }

        $dr_id = auth()->user()->role_id;

        //Update Nurse ID
        $dtb->dr_ID = $dr_id;
        $dtb->dtb_ScreenedAt = now();

        $dtb->save();

        return redirect()
            ->back()
            ->with('success', 'Candidate screening approved successfully.');
    }



    /**
     * Reject donor candidate screening
     */
    public function rejectDonorToBeDr(Request $request, $id)
    {
        // Validate reason
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $dtb = DonorToBe::findOrFail($id);

        // Update statuses
        $dtb->dtb_ScreeningStatus = 'failed';

        // Save rejection reason
        $dtb->dtb_ScreeningRemark = $request->reason;
        
        $dr_id = auth()->user()->role_id;
        //Update Nurse ID
        $dtb->dr_ID = $dr_id;
        $dtb->dtb_ScreenedAt = now();

        $dtb->save();

        return redirect()
            ->back()
            ->with('success', 'Candidate screening rejected.');
    }







}
