<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Donor;
use App\Models\DonorToBe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Twilio\Rest\Client;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): JsonResponse
    {
        // Debug: Check what data is coming in
        Log::info('Registration data received:', $request->all());

        // Validation rules
        $request->validate([
            // Contact step
            'contact_number' => ['required', 'string', 'max:20', 'regex:/^(\+?\d{1,3}[- ]?)?(\d{8,15})$/'],
            'terms' => ['required', 'accepted'],
            
            // Personal Information step
            'nric' => ['required', 'string', 'max:20', 'unique:donor,dn_NRIC'],
            'fullname' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date'],
            'email' => ['nullable', 'email', 'max:255', 'unique:donor,dn_Email'],
            'address' => ['required', 'string', 'max:500'],
            'parity' => ['required', 'integer', 'min:0'],
            
            // Delivery details
            'deliveryDate' => ['nullable', 'string'],
            'gestationWeek' => ['nullable', 'string'],
            
            // Health & Lifestyle step
            'infectiousRiskOption' => ['required', 'in:yes,no'],
            'infectiousRiskDetailText' => ['required_if:infectiousRiskOption,yes', 'nullable', 'string', 'max:255'],
            'medicationOption' => ['required', 'in:yes,no'],
            'medicationDetailText' => ['required_if:medicationOption,yes', 'nullable', 'string', 'max:255'],
            'recentIllnessOption' => ['required', 'in:yes,no'],
            'recentIllnessDetailText' => ['required_if:recentIllnessOption,yes', 'nullable', 'string', 'max:255'],
            'tobaccoAlcoholOption' => ['required', 'in:yes,no'],
            'dietaryAlertsOption' => ['required', 'in:yes,no'],
            'dietaryAlertsDetailText' => ['required_if:dietaryAlertsOption,yes', 'nullable', 'string', 'max:255'],
            
            // Availability step
            'availableDays' => ['required', 'string'],
            'timeSlots' => ['required', 'string'],
        ]);

        try {
            DB::beginTransaction();

            // Generate unique username and password
            $username = $this->generateUsername($request->fullname);
            $password = $this->generateTemporaryPassword();

            // Prepare delivery details as array (parse JSON)
            $deliveryDetails = $this->prepareDeliveryDetails($request);
            
            // Prepare availability as array (parse JSON)
            $availability = $this->prepareAvailability($request);

            // Create donor record
            $donor = Donor::create([
                'dn_NRIC' => $request->nric,
                'dn_FullName' => $request->fullname,
                'dn_Username' => $username,
                'dn_Password' => Hash::make($password),
                'first_login' => 1,
                'dn_DOB' => $request->dob,
                'dn_Contact' => $request->contact_number,
                'dn_Email' => $request->email,
                'dn_Address' => $request->address,
                'dn_Parity' => $request->parity,
                'dn_DeliveryDetails' => $deliveryDetails,
                'dn_Availability' => $availability,
                'dn_InfectionDeseaseRisk' => $request->infectiousRiskOption === 'yes' ? $request->infectiousRiskDetailText : null,
                'dn_Medication' => $request->medicationOption === 'yes' ? $request->medicationDetailText : null,
                'dn_RecentIllness' => $request->recentIllnessOption === 'yes' ? $request->recentIllnessDetailText : null,
                'dn_TobaccoAlcohol' => $request->tobaccoAlcoholOption === 'yes',
                'dn_DietaryAlerts' => $request->dietaryAlertsOption === 'yes' ? $request->dietaryAlertsDetailText : null,
            ]);

            // Create donor_to_be record for screening
            $donorToBe = DonorToBe::create([
                'dn_ID' => $donor->dn_ID,
                'dtb_ScreeningStatus' => 'pending',
                'dtb_InfectionDiseaseRisk' => $request->infectiousRiskOption === 'yes' ? $request->infectiousRiskDetailText : null,
                'dtb_Medication' => $request->medicationOption === 'yes' ? $request->medicationDetailText : null,
                'dtb_RecentIllness' => $request->recentIllnessOption === 'yes' ? $request->recentIllnessDetailText : null,
                'dtb_TobaccoAlcohol' => $request->tobaccoAlcoholOption === 'yes',
                'dtb_DietaryAlerts' => $request->dietaryAlertsOption === 'yes' ? $request->dietaryAlertsDetailText : null,
            ]);

            // Create user account
            $userEmail = $request->email ?: $username . '@donor.hmmc.com';

            // Initialize notification variables
            $emailSent = false;
            $whatsappSent = false;

            // Send welcome notifications if contact methods available
            if (!empty($request->email)) {
                $emailSent = $this->sendWelcomeEmail($donor, $password);
            }
            
            if (!empty($request->contact_number)) {
                $whatsappSent = $this->sendWelcomeWhatsApp($donor, $password);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Your application is under review. You will be notified after screening.',
                'has_email' => !empty($request->email),
                'has_whatsapp' => !empty($request->contact_number),
                'email_sent' => $emailSent,
                'whatsapp_sent' => $whatsappSent,
                'screening_required' => true
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 422);
        }
    }

    private function generateUsername($fullname): string
    {
        // Remove spaces and special characters, convert to lowercase
        $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $fullname));
        
        // If the name is too short, add some random characters
        if (strlen($baseUsername) < 3) {
            $baseUsername .= substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 3);
        }
        
        // Take first 8 characters as base username
        $baseUsername = substr($baseUsername, 0, 8);
        $username = $baseUsername;
        $counter = 1;

        // Check if username already exists and append number if needed
        while (Donor::where('dn_Username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
            
            // Safety limit to prevent infinite loop
            if ($counter > 100) {
                $username = $baseUsername . uniqid();
                break;
            }
        }

        return $username;
    }

    private function generateTemporaryPassword(): string
    {
        // Use Laravel's built-in password generator
        return \Illuminate\Support\Str::password(10);
    }

    private function prepareDeliveryDetails(Request $request): ?array
    {
        if ($request->parity <= 0 || empty($request->deliveryDate)) {
            return null;
        }

        // Parse JSON strings
        $deliveryDates = json_decode($request->deliveryDate, true) ?? [];
        $gestationWeeks = json_decode($request->gestationWeek, true) ?? [];

        if (empty($deliveryDates) || empty($gestationWeeks)) {
            return null;
        }

        $deliveryDetails = [];
        foreach ($deliveryDates as $index => $deliveryDate) {
            $deliveryDetails[] = [
                'child_number' => $index + 1,
                'delivery_date' => $deliveryDate,
                'gestation_week' => $gestationWeeks[$index] ?? null,
                'is_most_recent' => ($index + 1 == $request->parity),
            ];
        }

        return $deliveryDetails;
    }

    private function prepareAvailability(Request $request): ?array
    {
        if (empty($request->availableDays) || empty($request->timeSlots)) {
            return null;
        }

        // Parse JSON strings
        $availableDays = json_decode($request->availableDays, true) ?? [];
        $timeSlots = json_decode($request->timeSlots, true) ?? [];

        if (empty($availableDays) || empty($timeSlots)) {
            return null;
        }

        return [
            'available_days' => $availableDays,
            'time_slots' => $timeSlots
        ];
    }

    private function sendWelcomeEmail(Donor $donor, string $password): bool
    {
        try {
            Mail::send('emails.welcome-donor', [
                'donor' => $donor,
                'password' => $password,
                'loginUrl' => route('login')
            ], function ($message) use ($donor) {
                $message->to($donor->dn_Email)
                        ->subject('Welcome to HMMC - Registration Received');
            });

            Log::info("Welcome email sent to: {$donor->dn_Email}");
            return true;

        } catch (\Exception $e) {
            Log::error('Welcome email send failed: ' . $e->getMessage());
            return false;
        }
    }

    private function sendWelcomeWhatsApp(Donor $donor, string $password): bool
    {
        try {
            $message = "👋 Welcome {$donor->dn_FullName}!\n\n" .
                      "Thank you for registering with HMMC!\n\n" .
                      "Your application is under review. We'll notify you once screening is complete.\n\n" .
                      "Application ID: {$donor->dn_ID}\n" .
                      "Screening Status: Pending\n\n" .
                      "We appreciate your interest in helping mothers and babies in need! ❤️";

            // Generate WhatsApp link
            $cleanPhone = $this->formatPhoneNumber($donor->dn_Contact);
            $encodedMessage = urlencode($message);
            $whatsappLink = "https://wa.me/{$cleanPhone}?text={$encodedMessage}";

            Log::info("WhatsApp welcome message for {$donor->dn_FullName}: {$whatsappLink}");

            return true;

        } catch (\Exception $e) {
            Log::error('WhatsApp welcome message failed: ' . $e->getMessage());
            return false;
        }
    }

    private function formatPhoneNumber($phone): string
    {
        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $phone);
        
        // If starts with 0, replace with country code
        if (substr($phone, 0, 1) === '0') {
            $phone = '60' . substr($phone, 1); // Malaysia country code
        }
        
        // Ensure it doesn't start with + for WhatsApp link
        return ltrim($phone, '+');
    }
}