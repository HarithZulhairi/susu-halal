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

        // Upcoming appointments (Milk + Pumping Kit) - still from appointment tables
        $milkAppointments = MilkAppointment::where('dn_ID', $dn_id)
            ->where('appointment_datetime', '>=', now())
            ->get();

        $pumpingAppointments = PumpingKitAppointment::where('dn_ID', $dn_id)
            ->where('appointment_datetime', '>=', now())
            ->get();

        $upcomingAppointments = $milkAppointments->concat($pumpingAppointments)
            ->sortBy('appointment_datetime')
            ->values();

        // Last 6 months chart data (from Milk table - actual donation records)
        $monthLabels = [];
        $monthlyDonations = [];
        $monthlyFrequency = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthLabels[] = $month->format('F');

            $monthlyDonations[] = Milk::where('dn_ID', $dn_id)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('milk_volume');

            $monthlyFrequency[] = Milk::where('dn_ID', $dn_id)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // Total Donations (number of milk records for this donor)
        $totalDonations = Milk::where('dn_ID', $dn_id)->count();

        // Total Milk donated (sum of milk_volume)
        $totalMilk = Milk::where('dn_ID', $dn_id)->sum('milk_volume');

        // Total Bottles (post-pasteurization bottles from this donor's milk)
        $donorMilkIds = Milk::where('dn_ID', $dn_id)->pluck('milk_ID');
        $totalBottles = PostBottle::whereIn('milk_ID', $donorMilkIds)
            ->where('is_disposed', 0)
            ->count();

    return view('donor.donor_dashboard', compact(
        'upcomingAppointments',
        'monthLabels',
        'monthlyDonations',
        'monthlyFrequency',
        'totalDonations',
        'totalMilk',
        'totalBottles'
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

        // Active Donors (all approved donors in Donor table)
        $activeDonors = Donor::count();

        // Pending Milk Requests (requests waiting for nurse to process/allocate)
        $pendingMilkRequests = MilkRequest::where('status', 'Waiting')->count();

        // Total Milk Batches (all milk records the nurse oversees)
        $totalMilkBatches = Milk::count();

        // Available Bottles (post-pasteurization bottles ready for allocation, not disposed)
        $availableBottles = PostBottle::where('is_disposed', 0)->count();

        // ============================
        // 2. CHART DATA (Total Volume per Month)
        // ============================
        // This sums up the 'milk_volume' column for the current year
        $monthlyVolume = Milk::select(
                DB::raw('MONTH(created_at) as month'), 
                DB::raw('SUM(milk_volume) as total_volume')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total_volume', 'month');

        $months = [];
        $volumeData = [];

        // Loop 1 to 12 (January to December)
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('F', mktime(0, 0, 0, $i, 1));
            // Get the volume for month $i, or 0 if no data
            $volumeData[] = $monthlyVolume->get($i, 0); 
        }

        // ============================
        // 3. TODAY'S APPOINTMENTS
        // ============================
        $today = Carbon::today();

        $todayMilk = MilkAppointment::with('donor') // Eager load donor info
            ->whereDate('appointment_datetime', $today)
            ->get();

        $todayKit = PumpingKitAppointment::with('donor')
            ->whereDate('appointment_datetime', $today)
            ->get();

        // Merge collections and sort by time (earliest first)
        $todayAppointments = $todayMilk->merge($todayKit)->sortBy('appointment_datetime');

        // ============================
        // 4. RETURN VIEW
        // ============================
        return view('nurse.nurse_dashboard', compact(
            'activeDonors',
            'pendingMilkRequests',
            'totalMilkBatches',
            'availableBottles',
            'months',
            'volumeData',
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

        // 4. Active Bottles (not disposed)
        $activeBottles = PostBottle::where('is_disposed', 0)->count();

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
            'activeBottles',
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

        // Active Donors (all approved donors in the Donor table)
        $activeDonors = Donor::count();

        // Pending Milk Requests (status is 'Waiting' in the database)
        $pendingRequests = MilkRequest::where('status', 'Waiting')->count();

        // Approved Requests (requests approved by doctors)
        $approvedRequests = MilkRequest::where('status', 'Approved')->count();

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
            'approvedRequests' => $approvedRequests,
            
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
        // 1. Pending Approvals (milk not yet reviewed by Shariah)
        $pendingApprovals = Milk::whereNull('milk_shariahApproval')->count();

        // 2. Compliance Reviews (total milk that has been reviewed - approved or rejected)
        $complianceReviews = Milk::whereNotNull('milk_shariahApproval')->count();

        // 3. Fatwa Issued (milk approved by Shariah, value = 1)
        $fatwaIssued = Milk::where('milk_shariahApproval', 1)->count();

        // Compliance rate
        $complianceRate = $complianceReviews > 0 ? round(($fatwaIssued / $complianceReviews) * 100) : 0;
        $complianceChange = $complianceRate . '% compliant';

        // New pending today
        $newPendingToday = Milk::whereNull('milk_shariahApproval')
                              ->whereDate('created_at', Carbon::today())
                              ->count();
        $approvalsChange = $newPendingToday . ' new today';

        // Fatwa this month
        $fatwaThisMonth = Milk::where('milk_shariahApproval', 1)
                              ->whereYear('updated_at', Carbon::now()->year)
                              ->whereMonth('updated_at', Carbon::now()->month)
                              ->count();
        $fatwaChange = $fatwaThisMonth . ' this month';

        // 4. Chart Data (Last 7 Months)
        $months = [];
        $reviewedData = [];
        $fatwaData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            $year = $date->year;
            $month = $date->month;

            // Reviewed: has shariah approval in this month (based on updated_at)
            $reviewedData[] = Milk::whereNotNull('milk_shariahApproval')
                                  ->whereYear('updated_at', $year)
                                  ->whereMonth('updated_at', $month)
                                  ->count();

            // Fatwa Issued: Approved in this month
            $fatwaData[] = Milk::where('milk_shariahApproval', 1)
                               ->whereYear('updated_at', $year)
                               ->whereMonth('updated_at', $month)
                               ->count();
        }

        return view('shariah.shariah_dashboard', compact(
            'pendingApprovals', 
            'complianceReviews', 
            'fatwaIssued',
            'approvalsChange', 
            'complianceChange', 
            'fatwaChange',
            'months', 
            'reviewedData', 
            'fatwaData'
        ));
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
        // Total Users: Sum all role-specific tables (User table only has 4, but actual users = 9)
        $totalUsers = \App\Models\HmmcAdmin::count() + 
                      \App\Models\Nurse::count() + 
                      \App\Models\Doctor::count() + 
                      \App\Models\LabTech::count() + 
                      \App\Models\ShariahCommittee::count() + 
                      \App\Models\ParentModel::count() + 
                      \App\Models\Donor::count();

        // Active Donors: Count from Donor table (DonorToBe table is empty)
        $activeDonors = Donor::count();

        // Pending Send Credential: Donors who haven't been sent credentials yet
        $pendingCredentials = Donor::whereNull('dn_CredentialsSentAt')->count();

        // Pending Screening: Donors who haven't been sent credentials yet
        $systemAlerts = Donor::whereNull('dn_CredentialsSentAt')->count();

        // -----------------------
        // Chart Data: Donor Registrations & Active Donors
        // -----------------------
        $months = [];
        $registeredDonors = [];
        $activeDonorsMonthly = [];

        for ($i = 6; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M');

            // Donors registered in this month
            $registeredDonors[] = Donor::whereYear('created_at', $month->year)
                                      ->whereMonth('created_at', $month->month)
                                      ->count();

            // Cumulative active donors up to end of this month
            $activeDonorsMonthly[] = Donor::whereDate('created_at', '<=', $month->endOfMonth())
                                         ->count();
        }

        // -----------------------
        // Recent Donors Table
        // -----------------------
        $recentDonors = Donor::latest()
            ->take(10)
            ->get()
            ->map(function ($donor) {
                // Determine screening status
                $status = 'active';
                if (is_null($donor->dn_CredentialsSentAt)) {
                    $status = 'pending';
                }

                return (object)[
                    'id' => $donor->dn_ID,
                    'name' => $donor->dn_FullName ?? 'Unknown',
                    'email' => $donor->dn_Email ?? null,
                    'role' => 'donor',
                    'screeningStatus' => $status,
                    'created_at' => $donor->created_at,
                ];
            });

        return view('hmmc.hmmc_dashboard', compact(
            'totalUsers',
            'activeDonors',
            'pendingCredentials',
            'systemAlerts',
            'months',
            'registeredDonors',
            'activeDonorsMonthly',
            'recentDonors'
        ));
    }
}
