<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    protected $table = 'allocation';
    protected $primaryKey = 'allocation_ID';

    protected $fillable = [
        'request_ID',
        'post_ID',
        'ns_ID',
        'total_selected_milk',
        'storage_location',
        'allocation_milk_date_time',
        'dispense_date',
        'dispense_time',
    ];

    protected $casts = [
        'allocation_milk_date_time' => 'array',
    ];

    // Relationship to Post Bottle
    public function postBottles()
    {
        return $this->belongsTo(PostBottle::class, 'post_ID', 'post_ID');
    }

    // Relationship to Nurse
    public function nurse()
    {
        return $this->belongsTo(Nurse::class, 'ns_ID', 'ns_ID');
    }

    // Relationship to the Milk Request
    public function milkRequest()
    {
        return $this->belongsTo(Request::class, 'request_ID', 'request_ID');
    }

    public function request()
    {
        return $this->belongsTo(Request::class, 'request_ID', 'request_ID');
    }
}