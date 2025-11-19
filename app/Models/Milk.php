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
        'milk_shariahApproval',
        'milk_expiryDate',
        'milk_screeningStatus',
        'milk_screeningResult',
        'milk_homogenizeStatus',
        'milk_homogenizeResult',
        'milk_pasteurizeStatus',
        'milk_pasteurizeResult',
        'milk_labellingStatus',
        'milk_collectingStatus',
        'milk_storagingStatus',
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
