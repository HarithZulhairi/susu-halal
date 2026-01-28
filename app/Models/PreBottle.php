<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreBottle extends Model
{
    use HasFactory;

    protected $table = 'pre_bottles';
    
    protected $fillable = [
        'milk_ID', 
        'pre_bottle_code', 
        'pre_volume', 
        'pre_is_thawed'
    ];

    public function milk()
    {
        return $this->belongsTo(Milk::class, 'milk_ID', 'milk_ID');
    }
}