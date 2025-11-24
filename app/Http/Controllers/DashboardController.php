<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\MilkAppointment;
use App\Models\PumpingKitAppointment;
use App\Models\User;
use App\Models\Milk;
use App\Models\DonorToBe;
use App\Models\Donor;
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
    // NURSE DASHBOARD
    // ============================   
    public function nurse()
    {
        return view('nurse.nurse_dashboard');
    }

    // ============================
    // LABTECH DASHBOARD
    // ============================
    public function labtech()
    {
        // Total milk samples
        $totalSamples = Milk::count();

        // Processed samples (Screening completed)
        $processedSamples = Milk::whereNotNull('milk_stage1Result')->count();

        // Pending pasteurization (Stage 1 completed but Stage 2 not started)
        $pendingPasteurization = Milk::whereNotNull('milk_stage1Result')
                                    ->whereNull('milk_stage2StartDate')
                                    ->count();

        // Storage used (example, replace with actual calculation if needed)
        $storageUsed = '78%';

        // Chart data for the last 12 months
        $months = [];
        $processedMonthly = [];
        $dispatchedMonthly = [];
        $currentYear = date('Y');

        for ($i = 0; $i < 12; $i++) {
            $month = Carbon::now()->subMonths(11 - $i);
            $months[] = $month->format('M'); // Short month name

            // Processed: Stage 1 completed
            $processedMonthly[] = Milk::whereYear('milk_stage1EndDate', $month->year)
                                    ->whereMonth('milk_stage1EndDate', $month->month)
                                    ->count();

            // Dispatched: Stage 3 completed
            $dispatchedMonthly[] = Milk::whereYear('milk_stage3EndDate', $month->year)
                                    ->whereMonth('milk_stage3EndDate', $month->month)
                                    ->count();
        }

            // Fetch milk records for the table
            $milks = Milk::with('donor')
                ->orderByDesc('created_at')
                ->take(10) // optional: limit number shown on dashboard
                ->get();

        return view('labtech.labtech_dashboard', compact(
            'totalSamples',
            'processedSamples',
            'pendingPasteurization',
            'storageUsed',
            'months',
            'processedMonthly',
            'dispatchedMonthly',
            'milks'
        ));
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
public function hmmc()
{
    // -----------------------
    // Stats Cards
    // -----------------------
    $totalUsers = User::count(); // Total registered users
    $activeDonors = DonorToBe::where('dtb_ScreeningStatus', 'passed')->count(); // Active donors
    $totalDonations = MilkAppointment::sum('milk_amount'); // Total donations
    $systemAlerts = DonorToBe::where('dtb_ScreeningStatus', 'pending')->count();

    // -----------------------
    // Chart Data: Donor Registrations & Active Donors
    // -----------------------
    $months = [];
    $registeredDonors = [];
    $activeDonorsMonthly = [];

    for ($i = 6; $i >= 0; $i--) {
        $month = Carbon::now()->subMonths($i);
        $months[] = $month->format('M');

        $registeredDonors[] = DonorToBe::whereYear('created_at', $month->year)
                                       ->whereMonth('created_at', $month->month)
                                       ->count();

        $activeDonorsMonthly[] = DonorToBe::where('dtb_ScreeningStatus', 'passed')
                                          ->whereDate('created_at', '<=', $month->endOfMonth())
                                          ->count();
    }

    // -----------------------
    // Recent Donors Table
    // -----------------------
    $recentDonors = DonorToBe::with('donor')
        ->latest()
        ->take(10)
        ->get()
        ->map(function ($donorToBe) {
            return (object)[
                'id' => $donorToBe->dn_ID,
                'name' => $donorToBe->donor?->dn_FullName ?? 'Unknown', // get full name from Donor
                'email' => $donorToBe->donor?->dn_Email ?? null,
                'role' => 'donor',
                'screeningStatus' => $donorToBe->dtb_ScreeningStatus ?? 'passed',
                'created_at' => $donorToBe->created_at,
            ];
        });

    return view('hmmc.hmmc_dashboard', compact(
        'totalUsers',
        'activeDonors',
        'totalDonations',
        'systemAlerts',
        'months',
        'registeredDonors',
        'activeDonorsMonthly',
        'recentDonors'
    ));
}
}
