<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'role_id',
        'username',
        'ic_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relationships

    public function HmmcAdmin()
    {
        return $this->hasOne(HmmcAdmin::class, 'user_id', 'id');
    }

    public function Nurse()
    {
        return $this->hasOne(Nurse::class, 'user_id', 'id');
    }

    public function Doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id', 'id');
    }

    public function LabTech()
    {
        return $this->hasOne(LabTech::class, 'user_id', 'id');
    }

    public function ShariahCommittee()
    {
        return $this->hasOne(ShariahCommittee::class, 'user_id', 'id');
    }

    public function ParentModel()
    {
        return $this->hasOne(ParentModel::class, 'user_id', 'id');
    }

    public function Donor()
    {
        return $this->hasOne(Donor::class, 'user_id', 'id');
    }
}
