<?php

namespace App;

use App\Friend;
use App\PhoneNumber;
use App\Recording;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'dismissed_welcome' => 'boolean',
        'role' => 'integer',
    ];

    public function phoneNumbers()
    {
        return $this->hasMany(PhoneNumber::class);
    }

    public function friends()
    {
        return $this->hasMany(Friend::class);
    }

    public function recordings()
    {
        return $this->hasMany(Recording::class)->latest();
    }

    public static function findByPhoneNumber($number)
    {
        return PhoneNumber::where('number', $number)->firstOrFail()->user;
    }

    public function hasAVerifiedPhoneNumber()
    {
        return $this->phoneNumbers()->verified()->count() > 0;
    }

    public function isAdmin()
    {
        return $this->role === 42;
    }
}
