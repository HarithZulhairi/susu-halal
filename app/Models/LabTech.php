<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTech extends Model
{
    use HasFactory;

    protected $table = 'labtech';
    protected $primaryKey = 'lt_ID';

    protected $fillable = [
        'lt_Name',
        'lt_Email',
        'lt_Contact',
        'lt_NRIC',
        'lt_Address',
        'lt_Institution',
        'lt_Qualification',
        'lt_Certification',
        'lt_Specialization',
        'lt_YearsOfExperience',
        'lt_Password',
        'lt_Username',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
