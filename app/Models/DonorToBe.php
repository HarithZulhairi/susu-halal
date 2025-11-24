<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorToBe extends Model
{
    use HasFactory;

    protected $table = 'donor_to_be';
    protected $primaryKey = 'dtb_id';

    protected $fillable = [
        'dn_ID',
        'ns_ID',
        'dtb_ScreeningStatus',
        'dtb_ScreeningRemark',
        'dtb_ScreenedAt',
        'dtb_ScreeningNotes',
        'dtb_InfectionDiseaseRisk',
        'dtb_Medication',
        'dtb_RecentIllness',
        'dtb_TobaccoAlcohol',
        'dtb_DietaryAlerts',
    ];

    protected $casts = [
        'dtb_TobaccoAlcohol' => 'boolean',
        'dtb_ScreenedAt' => 'datetime',
    ];

    /**
     * Get the donor associated with this screening
     */
    public function donor()
    {
        return $this->belongsTo(Donor::class, 'dn_ID', 'dn_ID');
    }

    /**
     * Get the nurse who screened this donor
     */
    public function nurse()
    {
        return $this->belongsTo(Nurse::class, 'ns_ID', 'ns_ID');
    }

    /**
     * Get the screening status with badge class
     */
    public function getScreeningBadgeClassAttribute()
    {
        return [
            'pending' => 'screening-pending',
            'passed' => 'screening-passed',
            'failed' => 'screening-failed',
        ][$this->dtb_ScreeningStatus] ?? 'screening-pending';
    }

    /**
     * Check if screening is completed
     */
    public function getIsScreenedAttribute()
    {
        return !is_null($this->dtb_ScreenedAt);
    }

    /**
     * Scope for pending screenings
     */
    public function scopePending($query)
    {
        return $query->where('dtb_ScreeningStatus', 'pending');
    }

    /**
     * Scope for completed screenings
     */
    public function scopeScreened($query)
    {
        return $query->whereIn('dtb_ScreeningStatus', ['passed', 'failed']);
    }

    
}