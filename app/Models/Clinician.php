<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinician extends Model
{
    use HasFactory;

    protected $table = 'clinician';
    protected $primaryKey = 'cn_ID';
    protected $fillable = [
        'cn_Name', 
        'cn_Email', 
        'cn_Contact',
        'cn_NRIC',
        'cn_Address', 
        'cn_Institution', 
        'cn_Qualification',
        'cn_Cerification', 
        'cn_Specialization', 
        'cn_YearsOfExperience', 
        'cn_Password',
        'cn_Username',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
