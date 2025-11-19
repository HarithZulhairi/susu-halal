<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShariahCommittee extends Model
{
    use HasFactory;

    protected $table = 'shariahcomittee';
    protected $primaryKey = 'sc_ID';
    protected $fillable = [
        'sc_Name',
        'sc_Email',
        'sc_Contact',
        'sc_NRIC',
        'sc_Address',
        'sc_Institution',
        'sc_Qualification',
        'sc_Certification',
        'sc_Specialization',
        'sc_YearsOfExperience',
        'sc_Password',
        'sc_Username',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
