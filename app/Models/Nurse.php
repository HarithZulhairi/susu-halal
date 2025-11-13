<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    use HasFactory;

    protected $table = 'nurse';
    protected $primaryKey = 'ns_ID';
    protected $fillable = [
        'ns_Name',
        'ns_Email',
        'ns_Contact',
        'ns_NRIC',
        'ns_Address',
        'ns_Institution', 
        'ns_Qualification',
        'ns_Cerification',
        'ns_Specialization',
        'ns_YearsOfExperience',
        'ns_Password',
        'ns_Username',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
