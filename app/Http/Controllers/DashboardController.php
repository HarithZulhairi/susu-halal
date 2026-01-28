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
        // ====== STATS DATA ======
        // Active Donors (donors who passed screening)
        $activeDonors = Donor::whereHas('screening', function($query) {
            $query->where('dtb_ScreeningStatus', 'passed');
        })->count();

        // Pending Appointments (both milk and pumping kit)
        $pendingMilkAppointments = MilkAppointment::where('status', 'Pending')->count();
        $pendingKitAppointments = PumpingKitAppointment::where('status', 'Pending')->count();
        $pendingAppointments = $pendingMilkAppointments + $pendingKitAppointments;

        // Today's pending appointments for the change indicator
        $todayPendingAppointments = MilkAppointment::where('status', 'Pending')
            ->whereDate('appointment_datetime', Carbon::today())
            ->count() + PumpingKitAppointment::where('status', 'Pending')
            ->whereDate('appointment_datetime', Carbon::today())
            ->count();

        // Milk Requests (milk records that need processing - not yet completed)
        $milkRequests = Milk::whereNotIn('milk_Status', [
            'Distributing Completed', 
            'Completed',
            'Rejected'
        ])->count();

        // Milk requests from last week for percentage change
        $lastWeekMilkRequests = Milk::whereNotIn('milk_Status', [
            'Distributing Completed', 
            'Completed',
            'Rejected'
        ])->where('created_at', '>=', Carbon::now()->subWeek())
        ->count();

        $requestsChange = $lastWeekMilkRequests > 0 
            ? round((($milkRequests - $lastWeekMilkRequests) / $lastWeekMilkRequests) * 100) . '%' 
            : '0%';

        // Processing Queue (milk records in active processing stages)
        $processingQueue = Milk::whereIn('milk_Status', [
            'Screening',
            'Screening Completed', 
            'Labelling',
            'Labelling Completed',
            'Distributing'
        ])->count();

        // Urgent processing (milk expiring soon - within 3 days)
        $urgentQueue = Milk::where('milk_expiryDate', '<=', Carbon::now()->addDays(3))
            ->whereIn('milk_Status', ['Screening', 'Screening Completed', 'Labelling', 'Labelling Completed', 'Distributing'])
            ->count();

        // Active donors change from last month
        $lastMonthActiveDonors = Donor::whereHas('screening', function($query) {
            $query->where('dtb_ScreeningStatus', 'passed');
        })->where('created_at', '>=', Carbon::now()->subMonth())
        ->count();

        $donorsChange = $lastMonthActiveDonors > 0 
            ? round((($activeDonors - $lastMonthActiveDonors) / $lastMonthActiveDonors) * 100) . '%' 
            : '100%';

        // ====== GRAPH DATA (Monthly) ======
        $months = [];
        $milkData = [];
        $kitData = [];

        for ($i = 1; $i <= 12; $i++) {
            // Month labels
            $months[] = Carbon::create()->month($i)->format('M');

            // Count MilkAppointments per month
            $milkData[] = MilkAppointment::whereMonth('appointment_datetime', $i)->count();

            // Count PumpingKitAppointments per month
            $kitData[] = PumpingKitAppointment::whereMonth('appointment_datetime', $i)->count();
        }
            $dn_id = auth()->user()->role_id;
            $today = Carbon::today();

            // ====== TODAY'S APPOINTMENTS ======
            $todayMilk = MilkAppointment::join('donor', 'milk_appointments.dn_ID', '=', 'donor.dn_ID')
                ->select(
                    'milk_appointments.*',
                    'donor.dn_FullName',
                    'donor.dn_ID as donor_id'
                )
                ->whereDate('milk_appointments.appointment_datetime', $today)
                ->get();

            $todayKit = PumpingKitAppointment::join('donor', 'pumping_kit_appointments.dn_ID', '=', 'donor.dn_ID')
                ->select(
                    'pumping_kit_appointments.*',
                    'donor.dn_FullName',
                    'donor.dn_ID as donor_id'
                )
                ->whereDate('pumping_kit_appointments.appointment_datetime', $today)
                ->get();

            $todayAppointments = $todayMilk->merge($todayKit);

        return view('nurse.nurse_dashboard', [
            'activeDonors' => $activeDonors,
            'pendingAppointments' => $pendingAppointments,
            'milkRequests' => $milkRequests,
            'processingQueue' => $processingQueue,
            'months' => $months,
            'milkData' => $milkData,
            'kitData' => $kitData,
            'todayAppointments' => $todayAppointments,
        ]);
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
