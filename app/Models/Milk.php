<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milk extends Model
{
    use HasFactory;

    protected $table = 'milk';
    protected $primaryKey = 'milk_ID';

    protected $fillable = [
        'dn_ID',
        
        'milk_volume',
        'milk_Status',
        'milk_expiryDate',
        
        'milk_shariahApproval',
        'milk_shariahApprovalDate',
        'milk_shariahRemarks',

        // Keep Stage Dates/Times for the Batch Timeline
        'milk_stage1StartDate', 'milk_stage1EndDate',
        'milk_stage2StartDate', 'milk_stage2EndDate',
        'milk_stage3StartDate', 'milk_stage3EndDate',
        'milk_stage4StartDate', 'milk_stage4EndDate',
        'milk_stage5StartDate', 'milk_stage5EndDate',
    ];

    public function getFormattedIdAttribute()
    {
        return '#M' . $this->milk_ID;
    }

    // Relationships
    public function donor()
    {
        return $this->belongsTo(Donor::class, 'dn_ID', 'dn_ID');
    }

    public function preBottles()
    {
        return $this->hasMany(PreBottle::class, 'milk_ID', 'milk_ID');
    }

    public function postBottles()
    {
        return $this->hasMany(PostBottle::class, 'milk_ID', 'milk_ID');
    }
}