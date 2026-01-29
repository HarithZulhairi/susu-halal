<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ParentModel;
use App\Models\Request as MilkRequest;
use App\Models\MilkAppointment;
use App\Models\PumpingKitAppointment;
use App\Models\User;
use App\Models\nurse;
use App\Models\Milk;
use App\Models\DonorToBe;
use App\Models\Donor;
use App\Models\PostBottle;
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
        // ============================
        // 1. STATS CARDS
        // ============================

        // Active Donors
        $activeDonors = Donor::whereHas('screening', function($query) {
            $query->where('dtb_ScreeningStatus', 'passed');
        })->count();

        // Pending Appointments (Sum of Milk + Kit)
        $pendingAppointments = MilkAppointment::where('status', 'Pending')->count() 
                            + PumpingKitAppointment::where('status', 'Pending')->count();

        // Milk Requests (Placeholder or Real Model)
        // If you don't have a MilkRequest model yet, this defaults to 0 to prevent errors
        $milkRequests = class_exists('App\Models\MilkRequest') 
            ? \App\Models\MilkRequest::where('status', 'Pending')->count() 
            : 0; 

        // Processing Queue (Real Data)
        // Counts all milk records that have started (not null/Not Yet Started) 
        // but are not yet finished (Storage Completed)
        $processingQueue = Milk::whereNotIn('milk_Status', ['Not Yet Started', 'Storage Completed'])
                            ->whereNotNull('milk_Status')
                            ->count();

        // ============================
        // 2. CHART DATA (Total Volume)
        // ============================
        // We calculate the total VOLUME of milk collected per month for the chart
        $monthlyVolume = Milk::select(
                DB::raw('MONTH(created_at) as month'), 
                DB::raw('SUM(milk_volume) as total_volume')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total_volume', 'month'); // [month_number => volume]

        $months = [];
        $volumeData = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('F', mktime(0, 0, 0, $i, 1)); // Jan, Feb...
            $volumeData[] = $monthlyVolume->get($i, 0); // Get volume or 0
        }

        // ============================
        // 3. TODAY'S APPOINTMENTS
        // ============================
        $today = Carbon::today();

        $todayMilk = MilkAppointment::with('donor') // Eager load donor to avoid N+1
            ->whereDate('appointment_datetime', $today)
            ->get();

        $todayKit = PumpingKitAppointment::with('donor')
            ->whereDate('appointment_datetime', $today)
            ->get();

        // Merge and sort by time
        $todayAppointments = $todayMilk->merge($todayKit)->sortBy('appointment_datetime');

        return view('nurse.nurse_dashboard', compact(
            'activeDonors',
            'pendingAppointments',
            'milkRequests',
            'processingQueue',
            'months',
            'volumeData', // This variable matches the new Chart.js code
            'todayAppointments'
        ));
    }


    // ============================
    // LABTECH DASHBOARD
    // ============================
    public function labtech()
    {
        // 1. Total Milk Batches (Raw Donations)
        $totalSamples = Milk::count();

        // 2. Processed Batches (Completed Stage 1: Labelling)
        $processedSamples = Milk::whereNotNull('milk_stage1EndDate')->count();

        // 3. Pending Pasteurization (Completed Stage 2: Thawing, Not started Stage 3)
        $pendingPasteurization = Milk::whereNotNull('milk_stage2EndDate')
                                    ->whereNull('milk_stage3StartDate')
                                    ->count();

        // 4. Storage Used (Count of finalized Post-Pasteurization Bottles)
        // We count individual bottles in storage, not just batches
        $bottlesInStorage = PostBottle::whereNotNull('post_storage_location')->count();
        $storageUsed = $bottlesInStorage . ' Bottles';

        // 5. Chart Data (Last 12 Months)
        $months = [];
        $processedMonthly = []; // Batches labeled
        $dispatchedMonthly = []; // Batches fully completed (Stage 5)

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            $month = $date->month;
            $year = $date->year;

            // Processed: Based on Stage 1 End Date
            $processedMonthly[] = Milk::whereYear('milk_stage1EndDate', $year)
                                    ->whereMonth('milk_stage1EndDate', $month)
                                    ->count();

            // Dispatched/Stored: Based on Stage 5 End Date
            $dispatchedMonthly[] = Milk::whereYear('milk_stage5EndDate', $year)
                                    ->whereMonth('milk_stage5EndDate', $month)
                                    ->count();
        }

        // 6. Recent Records Table
        $milks = Milk::with('donor')
                    ->orderByDesc('created_at')
                    ->take(10)
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
        // ====== STATS DATA ======
        // Total Patients (parents with babies)
        $totalPatients = ParentModel::count(); // Adjust based on your actual model

        // Active Donors (donors who passed screening)
        $activeDonors = Donor::whereHas('screening', function($query) {
            $query->where('dtb_ScreeningStatus', 'passed');
        })->count();

        // Pending Milk Requests
        $pendingRequests = MilkRequest::where('status', 'Pending')->count();

        // ====== RECENT MILK REQUESTS FOR TABLE ======
        $recentRequests = MilkRequest::with(['parent', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->take(5) // Get only 5 most recent for dashboard
            ->get();

        // ====== GRAPH DATA ======
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
        $prescriptionsData = [30, 45, 55, 48, 60, 72, 90]; // Sample data
        $milkRequestsData = [20, 35, 40, 38, 50, 65, 75]; // Sample data

        return view('doctor.doctor_dashboard', [
            // Stats data
            'totalPatients' => $totalPatients,
            'activeDonors' => $activeDonors,
            'pendingRequests' => $pendingRequests,
            
            // Recent requests for table
            'recentRequests' => $recentRequests,
            
            // Graph data
            'months' => $months,
            'prescriptionsData' => $prescriptionsData,
            'milkRequestsData' => $milkRequestsData,
            
            // Change indicators (you can calculate these similarly to nurse dashboard)
            'patientChange' => '5%',
            'prescriptionChange' => '7%',
            'appointmentChange' => '2 completed',
        ]);
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
