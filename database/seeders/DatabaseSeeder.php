<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\HmmcAdmin;
use App\Models\Doctor;
use App\Models\Donor;
use App\Models\LabTech;
use App\Models\Nurse;
use App\Models\ParentModel;
use App\Models\ShariahCommittee;
use App\Models\Milk;
use App\Models\PreBottle;
use App\Models\PostBottle;
use App\Models\Request as MilkRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- 1. SEED DEFAULT USERS ---

        $admin = HmmcAdmin::create([
            'ad_NRIC' => '900101010101',
            'ad_Name' => 'HMMC Administrator',
            'ad_Username' => 'hmmc_admin',
            'ad_Password' => Hash::make('Admin123'),
            'ad_Address' => '123 Admin Street, Klang',
            'ad_Contact' => '0123456789',
            'ad_Email' => 'admin@hmmc.org',
            'ad_Gender' => 'Male',
        ]);

        $doctor = Doctor::create([
            'dr_NRIC' => '910101010101',
            'dr_Name' => 'Default Doctor',
            'dr_Username' => 'dr_default',
            'dr_Password' => Hash::make('Doctor123'),
            'dr_Address' => '12 Doctor Lane, Klang',
            'dr_Contact' => '0131234567',
            'dr_Email' => 'doctor@hmmc.org',
            'dr_Qualification' => 'MBBS',
            'dr_Certification' => 'Medical Board Certified',
            'dr_Institution' => 'UM Medical Center',
            'dr_Specialization' => 'Pediatrics',
            'dr_YearsOfExperience' => 8,
        ]);

        $donor = Donor::create([
            'dn_NRIC' => '920202020202',
            'dn_FullName' => 'Default Donor',
            'dn_Username' => 'dn_default',
            'dn_Password' => Hash::make('Donor123'),
            'dn_DOB' => '1992-02-02',
            'dn_Contact' => '0142345678',
            'dn_Email' => 'donor@hmmc.org',
            'dn_Address' => '45 Donor Avenue, Klang',
            'dn_MaritalStatus' => 'Married',
            'dn_HusbandConsent' => 'Yes',
            'dn_DonationType' => 'Voluntary',
            'dn_Religion' => 'Islam',
            'dn_ExcessBreastMilk' => 'Yes',
            'dn_MilkQuantity' => ['quantity' => 500, 'unit' => 'ml'],
            'dn_InfectionDeseaseRisk' => 'None',
            'dn_Medication' => 'None',
            'dn_RecentIllness' => 'None',
            'dn_SmokingStatus' => 'Non-smoker',
            'dn_PhysicalHealth' => 'Good',
            'dn_MentalHealth' => 'Good',
            'dn_TobaccoAlcohol' => 0,
            'dn_DietaryAlerts' => 'None',
            'dn_ConsentStatus' => 'Approved',
        ]);

        $labtech = LabTech::create([
            'lt_Name' => 'Default LabTech',
            'lt_Username' => 'lt_default',
            'lt_Password' => Hash::make('LabTech123'),
            'lt_Email' => 'labtech@hmmc.org',
            'lt_Contact' => '0153456789',
            'lt_NRIC' => '930303030303',
            'lt_Address' => '56 LabTech Street, Klang',
            'lt_Institution' => 'UiTM Health Science',
            'lt_Qualification' => 'Diploma in Medical Lab',
            'lt_Certification' => 'MLT Certified',
            'lt_Specialization' => 'Blood Testing',
            'lt_YearsOfExperience' => 5,
        ]);

        $nurse = Nurse::create([
            'ns_NRIC' => '940404040404',
            'ns_Name' => 'Default Nurse',
            'ns_Username' => 'ns_default',
            'ns_Password' => Hash::make('Nurse123'),
            'ns_Address' => '78 Nurse Road, Klang',
            'ns_Contact' => '0164567890',
            'ns_Email' => 'nurse@hmmc.org',
            'ns_Qualification' => 'Bachelor of Nursing',
            'ns_Certification' => 'Registered Nurse',
            'ns_Institution' => 'UM Nursing College',
            'ns_Specialization' => 'Neonatal Care',
            'ns_YearsOfExperience' => 6,
        ]);

        $parent = ParentModel::create([
            'pr_Name' => 'Default Parent',
            'pr_Password' => Hash::make('Parent123'),
            'pr_NRIC' => '950505050505',
            'pr_Address' => '123 Parent Street, Klang',
            'pr_Contact' => '0175678901',
            'pr_Email' => 'parent@hmmc.org',
            'pr_BabyName' => 'Baby Default',
            'pr_BabyDOB' => '2023-05-10',
            'pr_BabyGender' => 'Female',
            'pr_NICU' => 'No',
            'pr_BabyBirthWeight' => '3.2',
            'pr_BabyCurrentWeight' => '5.1',
        ]);

        // --- 2. SEED MILK DATA ---

        // MILK 1: Storage Completed, Shariah Approved, 100mL
        $milk1 = Milk::create([
            'dn_ID' => $donor->dn_ID,
            'milk_volume' => 120,
            'milk_Status' => 'Storage Completed',
            'milk_shariahApproval' => 1, // Approved
        ]);

        // Create PreBottles for Milk 1
        PreBottle::create([
            'milk_ID' => $milk1->milk_ID,
            'pre_bottle_code' => '#M1-B1',
            'pre_volume' => 120,
        ]);

        // Create PostBottles for Milk 1 (Simulate 2 bottles of 50ml)
        PostBottle::create([
            'milk_ID' => $milk1->milk_ID,
            'post_bottle_code' => '#M1-P1',
            'post_volume' => 30,
            'post_expiry_date' => Carbon::now()->addMonths(6),
            'post_micro_status' => 'NOT CONTAMINATED',
        ]);
        PostBottle::create([
            'milk_ID' => $milk1->milk_ID,
            'post_bottle_code' => '#M1-P2',
            'post_volume' => 30,
            'post_expiry_date' => Carbon::now()->addMonths(6),
            'post_micro_status' => 'NOT CONTAMINATED',
        ]);
        PostBottle::create([
            'milk_ID' => $milk1->milk_ID,
            'post_bottle_code' => '#M1-P3',
            'post_volume' => 30,
            'post_expiry_date' => Carbon::now()->addMonths(6),
            'post_micro_status' => 'NOT CONTAMINATED',
        ]);
        PostBottle::create([
            'milk_ID' => $milk1->milk_ID,
            'post_bottle_code' => '#M1-P4',
            'post_volume' => 30,
            'post_expiry_date' => Carbon::now()->addMonths(6),
            'post_micro_status' => 'NOT CONTAMINATED',
        ]);

        // MILK 2: Labelling Completed, 200mL
        $milk2 = Milk::create([
            'dn_ID' => $donor->dn_ID,
            'milk_volume' => 200,
            'milk_Status' => 'Labelling Completed',
            'milk_shariahApproval' => 0, // Pending
        ]);

        PreBottle::create([
            'milk_ID' => $milk2->milk_ID,
            'pre_bottle_code' => '#M2-B1',
            'pre_volume' => 200,
        ]);

        // --- 3. SEED REQUEST DATA ---

        MilkRequest::create([
            'dr_ID' => $doctor->dr_ID,
            'pr_ID' => $parent->pr_ID,
            'current_weight' => 5.1,
            'total_daily_volume' => 450,
            'current_baby_age' => '9 months',
            'gestational_age' => 38,
            'kinship_method' => 'no', // Standard non-kinship
            'volume_per_feed' => 37.5,
            'drip_total' => 360,
            'oral_total' => 90,
            'oral_per_feed' => 7.5,
            'feeding_tube' => 'Orgogastric',
            'oral_feeding' => 'Syringe',
            'feeding_start_date' => Carbon::now()->toDateString(),
            'feeding_start_time' => '08:00:00',
            'feeding_perday' => 12,
            'feeding_interval' => 2,
            'status' => 'Waiting',
        ]);
    }
}