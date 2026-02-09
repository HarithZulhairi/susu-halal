<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedRecord extends Model
{
    protected $table = 'feed_records';
    protected $primaryKey = 'feed_id';

    public $timestamps = false;

    protected $fillable = [
        'allocation_ID',
        'ns_ID',
        'fed_volume',
        'fed_at',
    ];

    protected $casts = [
        'fed_at' => 'datetime',
    ];

    public function allocations()
    {
        return $this->belongsTo(Allocation::class, 'allocation_ID', 'allocation_ID');
    }

    public function nurse()
    {
        return $this->belongsTo(Nurse::class, 'ns_ID', 'ns_ID');
    }
}