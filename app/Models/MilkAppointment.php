<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilkAppointment extends Model
{
    use HasFactory;

    protected $table = 'milk_appointments';

    // primary key is NOT default "id"
    protected $primaryKey = 'ma_ID';

    // auto-incrementing BIGINT → leave incrementing TRUE
    public $incrementing = true;

    protected $fillable = [
        'reference_num',
        'dn_ID',
        'milk_amount',
        'delivery_method',
        'location',
        'collection_address',
        'appointment_datetime',
        'remarks',
        'status',
    ];


    // Relationship: Appointment belongs to a donor
    public function donor()
    {
        return $this->belongsTo(Donor::class, 'dn_ID', 'dn_ID');
    }
}
