<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PumpingKitAppointment extends Model
{
    use HasFactory;

    protected $table = 'pumping_kit_appointments';
    protected $primaryKey = 'pk_ID';
    public $incrementing = true;
    protected $fillable = [
        'dn_ID',
        'reference_num',
        'kit_type',
        'appointment_datetime',
        'location',
        'reason',
        'status'
    ];

    // Relationship: Appointment belongs to a donor
    public function donor()
    {
        return $this->belongsTo(Donor::class, 'dn_ID', 'dn_ID');
    }
}
