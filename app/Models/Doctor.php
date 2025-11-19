<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctor';
    protected $primaryKey = 'dr_ID'; // Consider changing primary key name too
    protected $fillable = [
        'dr_Name', 
        'dr_Email', 
        'dr_Contact',
        'dr_NRIC',
        'dr_Address', 
        'dr_Institution', 
        'dr_Qualification',
        'dr_Certification', 
        'dr_Specialization', 
        'dr_YearsOfExperience', 
        'dr_Password',
        'dr_Username',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}