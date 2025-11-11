<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    protected $table = 'donor';
    protected $primaryKey = 'dn_ID';

    protected $fillable = [
        'dn_NRIC',
        'dn_FullName',
        'dn_DOB',
        'dn_Contact',
        'dn_Email',
        'dn_Address',
        'dn_InfectionDeseaseRisk',
        'dn_Medication',
        'dn_RecentIllness',
        'dn_TobaccoAlcohol',
        'dn_DietaryAlerts',
    ];

    // Accessor for formatted donor ID
    public function getFormattedIdAttribute()
    {
        return '#D' . $this->dn_ID;
    }

    // Example Usage
    // <p>Donor ID: {{ $donor->formatted_id }}</p>

    // Output
    // Donor ID: #D1
}
