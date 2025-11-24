<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\MilkAppointment;
use App\Models\PumpingKitAppointment;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // ============================
    // DONOR DASHBOARD
    // ============================
    public function donor()
    {
           $dn_id = auth()->user()->role_id; // Donor's ID

    // Upcoming appointments (Milk + Pumping Kit)
    $milkAppointments = MilkAppointment::where('dn_ID', $dn_id)
        ->where('appointment_datetime', '>=', now())
        ->get();

    $pumpingAppointments = PumpingKitAppointment::where('dn_ID', $dn_id)
        ->where('appointment_datetime', '>=', now())
        ->get();

    $upcomingAppointments = $milkAppointments->concat($pumpingAppointments)
        ->sortBy('appointment_datetime')
        ->values();

    // Last 6 months stats
    $monthLabels = [];
    $monthlyDonations = [];
    $monthlyFrequency = [];

    for ($i = 5; $i >= 0; $i--) {
        $month = Carbon::now()->subMonths($i);
        $monthLabels[] = $month->format('F'); // Full month names

        $monthlyDonations[] = MilkAppointment::where('dn_ID', $dn_id)
            ->whereYear('appointment_datetime', $month->year)
            ->whereMonth('appointment_datetime', $month->month)
            ->sum('milk_amount');

        $monthlyFrequency[] = MilkAppointment::where('dn_ID', $dn_id)
            ->whereYear('appointment_datetime', $month->year)
            ->whereMonth('appointment_datetime', $month->month)
            ->count();
    }

    // Total counts for cards
    $totalDonations = $milkAppointments->count();
    $totalMilk = $milkAppointments->sum('milk_amount');
    $totalRecipients = MilkAppointment::where('dn_ID', $dn_id)
        ->whereNotNull('milk_amount')
        ->count();

    return view('donor.donor_dashboard', compact(
        'upcomingAppointments',
        'monthLabels',
        'monthlyDonations',
        'monthlyFrequency',
        'totalDonations',
        'totalMilk',
        'totalRecipients'
    ));
    }


    // ============================
    // LABTECH DASHBOARD
    // ============================
    public function labtech()
    {
        return view('labtech.labtech_dashboard');
    }

    // ============================
    // DOCTOR DASHBOARD
    // ============================
    public function doctor()
    {
        return view('doctor.doctor_dashboard');
    }

    // ============================
    // SHARIAH DASHBOARD
    // ============================
    public function shariah()
    {
        return view('shariah.shariah_dashboard');
    }

    // ============================
    // PARENT DASHBOARD
    // ============================
    public function parent()
    {
        return view('parent.parent_dashboard');
    }

    // ============================
    // ADMIN DASHBOARD
    // ============================
    public function admin()
    {
        return view('hmmc.hmmc_dashboard');
    }
}
