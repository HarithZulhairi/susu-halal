<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostBottle extends Model
{
    use HasFactory;

    protected $table = 'post_bottles';

    protected $fillable = [
        'milk_ID', 
        'pr_ID', 
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

    // Relationship to Parent (Receiver)
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'pr_ID', 'pr_ID');
    }
}