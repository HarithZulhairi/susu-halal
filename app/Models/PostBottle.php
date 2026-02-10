<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostBottle extends Model
{
    use HasFactory;

    protected $table = 'post_bottles';
    protected $primaryKey = 'post_ID';

    protected $fillable = [
        'milk_ID', 
        'post_bottle_code', 
        'post_volume',
        'post_pasteurization_date', 
        'post_expiry_date',
        'post_micro_total_viable', 
        'post_micro_entero', 
        'post_micro_staph', 
        'post_micro_status',
        'post_storage_location'
    ];

    public function milk()
    {
        return $this->belongsTo(Milk::class, 'milk_ID', 'milk_ID');
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class, 'post_ID', 'post_ID');
    }

    public function donor() {
        return $this->belongsTo(Donor::class, 'dn_ID', 'dn_ID');
    }
}