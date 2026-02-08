<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    protected $table = 'allocation';
    protected $primaryKey = 'allocation_ID';

    protected $fillable = [
        'request_ID',
        'postBottle_ID',
        'allocated_volume',
        'storage_location',
        'allocated_at',
        'dispensed_at',
        'dispensed_by',
    ];

    public function postBottle()
    {
        return $this->belongsTo(PostBottle::class, 'postBottle_ID');
    }

    // Relationship to the Milk Request
    public function milkRequest()
    {
        return $this->belongsTo(Request::class, 'request_ID', 'request_ID');
    }
}

