<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $table = 'request';        // table name
    protected $primaryKey = 'request_ID'; // primary key column
    
    // Laravel expects incrementing BIGINT, so keep this:
    public $incrementing = true;
    protected $keyType = 'int';

    // Allow mass assignment
    protected $fillable = [
        'dr_ID',
        'pr_ID',
        'current_weight',
        'recommended_volume',
        'feeding_start_date',
        'feeding_start_time',
        'feeding_perday',
        'feeding_interval',
        'status',
    ];

    /**
     * Relationships
     */

    // A request belongs to a doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'dr_ID', 'dr_ID');
    }

    // A request belongs to a parent
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'pr_ID', 'pr_ID');
    }

    // --- ADD THIS FUNCTION ---
    public function allocation()
    {
        // One Request has Many Allocations (Milk Units)
        return $this->hasMany(Allocation::class, 'request_ID', 'request_ID');
    }
}
