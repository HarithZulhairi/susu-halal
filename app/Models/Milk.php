<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milk extends Model
{
    use HasFactory;

    // Specify the table name (since it's not plural)
    protected $table = 'milk';

    // Primary key
    protected $primaryKey = 'milk_ID';

    // Allow mass assignment for these fields
    protected $fillable = [
        'dn_ID',
        'pr_ID',
        'milk_volume',
        'milk_expiryDate',
        'milk_shariahApproval',
        'milk_shariahApprovalDate',
        'milk_shariahRemarks',
        'milk_Status',

        'milk_stage1StartDate',
        'milk_stage1EndDate',
        'milk_stage1StartTime',
        'milk_stage1EndTime',
        'milk_stage1Result',

        'milk_stage2StartDate',
        'milk_stage2EndDate',
        'milk_stage2StartTime',
        'milk_stage2EndTime',

        'milk_stage3StartDate',
        'milk_stage3EndDate',
        'milk_stage3EndTime',
        'milk_stage3StartTime',
    ];

    // Accessor for custom display ID
    public function getFormattedIdAttribute()
    {
        return '#M' . $this->milk_ID;
    }

    // Each milk record belongs to a donor
    public function donor()
    {
        return $this->belongsTo(Donor::class, 'dn_ID', 'dn_ID');
    }

    // Each milk record may belong to a parent (nullable)
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'pr_ID', 'pr_ID');
    }
}
