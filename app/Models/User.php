<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the username attribute for authentication.
     */
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    /**
     * Route notifications for the mail channel.
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    public function donor()
    {
        return $this->hasOne(Donor::class, 'user_id');
    }

    public function parentProfile()
    {
        return $this->hasOne(ParentProfile::class, 'user_id'); // use different model name if needed
    }

    public function nurse()
    {
        return $this->hasOne(Nurse::class, 'user_id');
    }

    public function clinician()
    {
        return $this->hasOne(Clinician::class, 'user_id');
    }

    public function hmmcAdmin()
    {
        return $this->hasOne(HmmcAdmin::class, 'user_id');
    }

    public function shariahCommittee()
    {
        return $this->hasOne(ShariahCommittee::class, 'user_id');
    }

}