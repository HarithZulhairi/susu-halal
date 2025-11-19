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
        'dn_Username',
        'dn_Password',
        'first_login',
        'dn_DOB',
        'dn_Contact',
        'dn_Email',
        'dn_Address',
        'dn_Parity',
        'dn_DeliveryDetails',
        'dn_Availability',
        'dn_InfectionDeseaseRisk',
        'dn_Medication',
        'dn_RecentIllness',
        'dn_TobaccoAlcohol',
        'dn_DietaryAlerts',
        'user_id',
    ];

    protected $casts = [
        'dn_TobaccoAlcohol' => 'boolean',
        'dn_DOB' => 'date',
        'dn_DeliveryDetails' => 'array',
        'dn_Availability' => 'array',
    ];

    // Accessor for formatted donor ID
    public function getFormattedIdAttribute()
    {
        return '#D' . $this->dn_ID;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function screening()
    {
        return $this->hasOne(DonorToBe::class, 'dn_ID', 'dn_ID');
    }

    public function getIsActiveAttribute()
    {
        return $this->screening && $this->screening->dtb_ScreeningStatus === 'passed';
    }
}