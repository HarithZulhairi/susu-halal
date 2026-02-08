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
        'total_selected_milk',
        'storage_location',
        'allocation_milk_date_time',
    ];

    protected $casts = [
        'allocation_milk_date_time' => 'array',
    ];

    // Relationship to Milk
    public function milk()
    {
        return $this->belongsTo(Milk::class, 'milk_ID', 'milk_ID');
    }

    // Relationship to the Milk Request
    public function milkRequest()
    {
        return $this->belongsTo(Request::class, 'request_ID', 'request_ID');
    }
}