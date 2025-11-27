<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parent';
    protected $primaryKey = 'pr_ID';

    protected $fillable = [
        'pr_Name',
        'pr_NRIC',
        'pr_Address',
        'pr_Contact',
        'pr_Email',
        'pr_BabyName',
        'pr_BabyDOB',
        'pr_NICU',
        'pr_BabyGender',
        'pr_BabyBirthWeight',
        'pr_BabyCurrentWeight',
        'pr_Password',
        'pr_Username',
        'user_id',
    ];

    /**
     * Relationship: One parent may receive multiple milk donations.
     */
    public function milk()
    {
        return $this->hasMany(Milk::class, 'pr_ID');
    }

    public function requests()
    {
        // A Parent has many Requests
        return $this->hasMany(Request::class, 'pr_ID', 'pr_ID');
    }

    /**
     * Accessor for formatted Parent ID, e.g. #P1, #P2, ...
     */
    public function getFormattedIdAttribute()
    {
        return '#P' . $this->pr_ID;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // Example Usage

    /*
        $parent = ParentModel::find(1);

        echo $parent->formatted_id;  // Output: #P1
        echo $parent->pr_Name;       // Output: Parent’s name

        foreach ($parent->milk as $milk) {
            echo $milk->milk_volume;
        }
    */
}
