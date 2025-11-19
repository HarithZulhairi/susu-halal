<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HmmcAdmin extends Model
{
    use HasFactory;

    protected $table = 'hmmcadmin';
    protected $primaryKey = 'ad_Admin';
    protected $fillable = [
        'ad_Name',
        'ad_Email', 
        'ad_Contact', 
        'ad_NRIC', 
        'ad_Address',
        'ad_Gender', 
        'ad_Password',
        'ad_Username',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
