<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    protected $table = 'allocation';
    protected $primaryKey = 'allocation_ID';

    protected $fillable = [
        'request_ID',
<<<<<<< Updated upstream
        'post_ID',
        'total_selected_milk',
=======
        'postBottle_ID',
        'allocated_volume',
>>>>>>> Stashed changes
        'storage_location',
        'allocated_at',
        'dispensed_at',
        'dispensed_by',
    ];

<<<<<<< Updated upstream
    protected $casts = [
        'allocation_milk_date_time' => 'array',
    ];

    // Relationship to Milk
    public function milk()
=======
    public function postBottle()
>>>>>>> Stashed changes
    {
        return $this->belongsTo(PostBottle::class, 'postBottle_ID');
    }

    // Relationship to the Milk Request
    public function milkRequest()
    {
        return $this->belongsTo(Request::class, 'request_ID', 'request_ID');
    }
<<<<<<< Updated upstream
}
=======
}

>>>>>>> Stashed changes
