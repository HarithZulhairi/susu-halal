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
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1️⃣ Admin
        HmmcAdmin::create([
            'ad_NRIC' => '900101010101',
            'ad_Name' => 'HMMC Administrator',
            'ad_Username' => 'hmmc_admin',
            'ad_Password' => Hash::make('Admin123'),
            'ad_Address' => '123 Admin Street, Klang',
            'ad_Contact' => '0123456789',
            'ad_Email' => 'admin@hmmc.org',
            'ad_Gender' => 'Male',
        ]);

        // 2️⃣ Doctor
        Doctor::create([
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

        // 3️⃣ Donor
        Donor::create([
            'dn_NRIC' => '920202020202',
            'dn_FullName' => 'Default Donor 1',
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
            'dn_MilkQuantity' => ['quantity' => 500, 'unit' => 'ml'], // Saved as JSON array
            'dn_InfectionDeseaseRisk' => 'None',
            'dn_Medication' => 'None',
            'dn_RecentIllness' => 'None',
            'dn_SmokingStatus' => 'Non-smoker',
            'dn_PhysicalHealth' => 'Good',
            'dn_MentalHealth' => 'Good',
            'dn_TobaccoAlcohol' => 0,
            'dn_DietaryAlerts' => 'None',
            'dn_ConsentStatus' => 'Pending',
        ]);

        // 3️⃣ Donor
        Donor::create([
            'dn_NRIC' => '930303030303',
            'dn_FullName' => 'Default Donor 2',
            'dn_Username' => 'dn_default2',
            'dn_Password' => Hash::make('Donor123'),
            'dn_DOB' => '1998-02-02',
            'dn_Contact' => '0112456779',
            'dn_Email' => 'donor@hmmc.org',
            'dn_Address' => '452 Melaka Avenue, Klang',
            'dn_MaritalStatus' => 'Married',
            'dn_HusbandConsent' => 'Yes',
            'dn_DonationType' => 'Voluntary',
            'dn_Religion' => 'Islam',
            'dn_ExcessBreastMilk' => 'Yes',
            'dn_MilkQuantity' => ['quantity' => 500, 'unit' => 'ml'], // Saved as JSON array
            'dn_InfectionDeseaseRisk' => 'None',
            'dn_Medication' => 'None',
            'dn_RecentIllness' => 'None',
            'dn_SmokingStatus' => 'Non-smoker',
            'dn_PhysicalHealth' => 'Good',
            'dn_MentalHealth' => 'Good',
            'dn_TobaccoAlcohol' => 0,
            'dn_DietaryAlerts' => 'None',
            'dn_ConsentStatus' => 'Pending',
        ]);

        // 4️⃣ Lab Technician
        LabTech::create([
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

        // 5️⃣ Nurse
        Nurse::create([
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

        // 6️⃣ Parent
        ParentModel::create([
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

        // 7️⃣ Shariah Committee
        ShariahCommittee::create([
            'sc_NRIC' => '960606060606',
            'sc_Name' => 'Default Shariah Officer',
            'sc_Username' => 'sc_default',
            'sc_Password' => Hash::make('Shariah123'),
            'sc_Address' => '89 Shariah Lane, Klang',
            'sc_Contact' => '0186789012',
            'sc_Email' => 'shariah@hmmc.org',
            'sc_Qualification' => 'BA Islamic Studies',
            'sc_Certification' => 'Shariah Certified',
            'sc_Institution' => 'IIUM',
            'sc_Specialization' => 'Bioethics',
            'sc_YearsOfExperience' => 4,
        ]);
    }
}
